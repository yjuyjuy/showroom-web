<?php

namespace App\Http\Controllers\api\v3\shared;

use App\Message;
use App\User;
use App\Vendor;
use App\Retailer;
use App\Http\Controllers\Controller;
use App\Jobs\NotifyRecipient;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class MessageController extends Controller
{
    public function pull()
    {
        $ITEMS_PER_REQUEST = 10;
        $user =  auth()->user();
        $query = Message::take($ITEMS_PER_REQUEST);
        $query->where(function ($query) use ($user) {
            $query->where('recipient_type', User::class)->where('recipient_id', $user->id);
        });
        if ($vendor = $user->vendor) {
            $query->where(function ($query) use ($vendor) {
                $query->where('recipient_type', Vendor::class)->where('recipient_id', $vendor->id);
            });

            if ($retailer = $vendor->retailer) {
                $query->where(function ($query) use ($retailer) {
                    $query->where('recipient_type', Retailer::class)->where('recipient_id', $retailer->id);
                });
            }
        }
        $messages = $query->get();
        $count = $query->count();
        return [
            'has_more' => $count > 0,
            'messages' => $messages,
        ];
    }

    public function push(Request $request)
    {
        $user =  auth()->user();
        $data = $request->validate([
            'sender_id' => 'required|int',
            'sender_type' => ['required', Rule::in(['user', 'vendor', 'retailer'])],
            'recipient_id' => 'required|int',
            'recipient_type' => ['required', Rule::in(['user', 'vendor', 'retailer'])],
            'content' => 'required|string|max:510',
        ]);
        foreach (['sender_type', 'recipient_type'] as $column) {
            switch ($data[$column]) {
                case 'user':
                    $data[$column] = User::class;
                    break;
                case 'vendor':
                    $data[$column] = Vendor::class;
                    break;
                case 'retailer':
                    $data[$column] = Retailer::class;
                    break;
            }
        }
        $sender = $data['sender_type']::find($data['sender_id']);
        if (!$sender || ($sender != $user && $sender != $user->vendor && (!$user->vendor || $sender != $user->vendor->retailer))) {
            abort(403, 'Invalid sender information');
        }
        $recipient = $data['recipient_type']::find($data['recipient_id']);
        if (!$recipient) {
            abort(403, 'Invalid recipient information');
        }
        $message = Message::create($data)->fresh();
        NotifyRecipient::dispatch($message);
        return $message;
    }
}
