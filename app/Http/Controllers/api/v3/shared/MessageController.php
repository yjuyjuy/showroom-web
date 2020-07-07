<?php

namespace App\Http\Controllers\api\v3\shared;

use App\Message;
use App\User;
use App\Vendor;
use App\Retailer;
use App\Http\Controllers\Controller;
use App\Jobs\NotifyRecipient;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Validation\Rule;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;

class MessageController extends Controller
{
    public function pull()
    {
        $ITEMS_PER_REQUEST = 10;
        $user =  auth()->user();
        $query = Message::take($ITEMS_PER_REQUEST);
        $data = request()->validate(['from' => 'required|integer|min:0']);
        $from = (int) $data['from'];
        if ($from > 0) {
            $query->where('id', '>', $from);
        } else {
            $query->where('created_at', '>', now()->subDays(7));
        }
        $user_accounts = new EloquentCollection([$user]);
        $query->where(function ($query) use ($user, $user_accounts) {
            $query->orWhere(function ($query) use ($user) {
                $query->where('recipient_type', User::class)->where('recipient_id', $user->id);
            });
            if ($vendor = $user->vendor) {
                $user_accounts->add($vendor);
                $query->orWhere(function ($query) use ($vendor) {
                    $query->where('recipient_type', Vendor::class)->where('recipient_id', $vendor->id);
                });

                if ($retailer = $vendor->retailer) {
                    $user_accounts->add($retailer);
                    $query->orWhere(function ($query) use ($retailer) {
                        $query->where('recipient_type', Retailer::class)->where('recipient_id', $retailer->id);
                    });
                }
            }
        });
        $messages = $query->get()->load(['sender', 'sender.image', 'recipient', 'recipient.image']);
        $messages->each(function ($message, $key) use ($user_accounts) {
            $message->from_me = $user_accounts->contains($message->sender);
        });
        $count = $query->count() - $messages->count();
        return [
            'messages' => $messages,
            'user_accounts' => $user_accounts,
            'has_more' => $count > 0,
        ];
    }

    public function push(Request $request)
    {
        $user =  auth()->user()->load(['vendor', 'vendor.retailer']);
        $data = $request->validate([
            'recipient_id' => 'required|int',
            'recipient_type' => ['required', Rule::in([User::class, Vendor::class, Retailer::class])],
            'content' => 'required|string|max:510',
            'sent_at' => 'required|integer|min:1',
            'reply_to' => 'sometimes|nullable|integer',
        ]);
        $recipient = $data['recipient_type']::findOrFail($data['recipient_id']);
        $sent_at = Carbon::createFromTimestamp($data['sent_at']);
        $sender = $user;
        if ($data['reply_to'] && $message = Message::find($data['reply_to'])) {
            $user_accounts = new EloquentCollection([$user]);
            if ($user->vendor) {
                $user_accounts->add($user->vendor);
                if ($user->vendor->retailer) {
                    $user_accounts->add($user->vendor->retailer);
                }
            }
            if ($user_accounts->contains($message->sender)) {
                $sender = $message->sender;
            } else if ($user_accounts->contains($message->recipient) {
                $sender = $message->recipient;
            }
        } else {
            if ($recipient instanceof User) {
                if ($user->vendor) {
                    if ($user->vendor->retailer) {
                        $sender = $user->vendor->retailer;
                    } else {
                        $sender = $user->vendor;
                    }
                }
            } else if ($recipient instanceof Vendor && $user->vendor) {
                $sender = $user->vendor;                
            }
        }
        $message = new Message();
        $message->content = $data['content'];
        $message->sent_at = $sent_at;
        $message->sender()->associate($sender);
        $message->recipient()->associate($recipient);
        $message->save();
        NotifyRecipient::dispatch($message->refresh());
        $message->from_me = true;
        return $message;
    }
}
