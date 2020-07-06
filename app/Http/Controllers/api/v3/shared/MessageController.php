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

class MessageController extends Controller
{
    public function pull()
    {
        $ITEMS_PER_REQUEST = 10;
        $user =  auth()->user();
        $query = Message::take($ITEMS_PER_REQUEST);
        $data = request()->validate(['from' => 'required|integer|min:0']);
        $from = (int) $data['from'];
        $query->where('id', '>', $from);
        $user_accounts = [$user];
        $query->where(function ($query) use ($user, $user_accounts) {
            $query->orWhere(function ($query) use ($user) {
                $query->where('recipient_type', User::class)->where('recipient_id', $user->id);
            });
            if ($vendor = $user->vendor) {
                $user_accounts[] = $vendor;
                $query->orWhere(function ($query) use ($vendor) {
                    $query->where('recipient_type', Vendor::class)->where('recipient_id', $vendor->id);
                });

                if ($retailer = $vendor->retailer) {
                    $user_accounts[] = $retailer;
                    $query->orWhere(function ($query) use ($retailer) {
                        $query->where('recipient_type', Retailer::class)->where('recipient_id', $retailer->id);
                    });
                }
            }
        });
        $messages = $query->get()->load(['sender', 'sender.image', 'recipient', 'recipient.image']);
        $messages->each(function ($message, $key) use ($user_accounts) {
            foreach ($user_accounts as $account) {
                if ($message->sender->is($account)) {
                    $message->from_me = true;
                }
            }
            $message->from_me = false;
        });
        $count = $query->count();
        return [
            'messages' => $messages,
            'user_accounts' => $user_accounts,
            'has_more' => $count > $messages->count(),
        ];
    }

    public function push(Request $request)
    {
        $user =  auth()->user();
        $data = $request->validate([
            'recipient_id' => 'required|int',
            'recipient_type' => ['required', Rule::in([User::class, Vendor::class, Retailer::class])],
            'content' => 'required|string|max:510',
            'sent_at' => 'required|integer|min:1',
        ]);
        $recipient = $data['recipient_type']::findOrFail($data['recipient_id']);
        $sent_at = Carbon::createFromTimestamp($data['sent_at']);
        if ($user->vendor) {
            if ($user->vendor == $recipient) {
                $sender = $user;
            } else if ($user->vendor->retailer) {
                if ($user->vendor->retailer == $recipient) {
                    $sender = $user;
                } else {
                    $sender = $user->vendor->retailer;
                }
            } else {
                $sender = $user->vendor;
            }
        } else {
            $sender = $user;
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
