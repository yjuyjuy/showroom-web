<?php

namespace App\Http\Controllers\api\v3\shared;

use App\Events\NewMessageReceivedEvent;
use App\Events\NewMessageSentEvent;
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
    public function pull(Request $request)
    {
        $ITEMS_PER_REQUEST = 10;
        $user =  $request->user();
        $query = Message::take($ITEMS_PER_REQUEST);
        $data = $request->validate(['from' => 'required|integer|min:0']);
        $from = (int) $data['from'];
        if ($from > 0) {
            $query->where('id', '>', $from);
        } else {
            $query->where('created_at', '>', now()->subDays(7));
        }
        $user_accounts = new EloquentCollection([$user]);
        $query->where(function ($query) use ($user, $user_accounts) {
            foreach (['sender', 'recipient'] as $role) {
                $query->orWhere(function ($query) use ($user, $role) {
                    $query->where($role . '_type', User::class)->where($role . '_id', $user->id);
                });
            }
            if ($vendor = $user->vendor) {
                $user_accounts->add($vendor);
                foreach (['sender', 'recipient'] as $role) {
                    $query->orWhere(function ($query) use ($vendor, $role) {
                        $query->where($role . '_type', Vendor::class)->where($role . '_id', $vendor->id);
                    });
                }


                if ($retailer = $vendor->retailer) {
                    $user_accounts->add($retailer);
                    foreach (['sender', 'recipient'] as $role) {
                        $query->orWhere(function ($query) use ($retailer, $role) {
                            $query->where($role . '_type', Retailer::class)->where($role . '_id', $retailer->id);
                        });
                    }
                }
            }
        });
        $messages = $query->get()->load(['sender', 'sender.image', 'recipient', 'recipient.image']);
        $messages->each(function ($message, $key) use ($user_accounts) {
            $message->from_user = $user_accounts->contains($message->sender);
        });
        $count = $query->count() - $messages->count();
        return [
            'messages' => $messages,
            'has_more' => $count > 0,
        ];
    }

    public function push(Request $request)
    {
        $valid_type_rule = Rule::in([User::class, Vendor::class, Retailer::class]);
        $data = $request->validate([
            'sender_id' => 'required|int',
            'sender_type' => ['required', $valid_type_rule],
            'recipient_id' => 'required|int',
            'recipient_type' => ['required', $valid_type_rule],
            'content' => 'required|string|max:510',
            'created_at' => 'required|integer|min:1',
        ]);
        $recipient = $data['recipient_type']::findOrFail($data['recipient_id']);
        $sender = $data['sender_type']::findOrFail($data['sender_id']);
        $this->authorize('sendAs', $sender);
        $message = new Message();
        $message->content = $data['content'];
        $message->sent_at = Carbon::createFromTimestamp($data['created_at']);
        $message->sender()->associate($sender);
        $message->recipient()->associate($recipient);
        $message->save();
        $this->created($message);
        return $message;
    }

    public function created(Message $message)
    {
        $message->refresh()->loadMissing(['sender', 'sender.image', 'recipient', 'recipient.image']);
        event(new NewMessageSentEvent($message));
        event(new NewMessageReceivedEvent($message));
    }
}
