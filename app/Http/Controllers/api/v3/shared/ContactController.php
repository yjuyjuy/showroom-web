<?php

namespace App\Http\Controllers\api\v3\shared;

use App\Message;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ContactController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $data = request()->validate([
            'message_ids' => 'required|array',
            'message_ids.*' => 'interger|exists:messages,id',
        ]);
        $messages = Message::find($data['message_ids'])->load(['sender', 'recipient']);
        $contacts = $messages->map(function ($message) use ($user) {
            return $message->fromUser($user) ? $message->recipient : $message->sender;
        });
        return [
            'contacts' => $contacts,
        ];
    }
}
