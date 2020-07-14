<?php

namespace App\Http\Controllers\api\v3\shared;

use App\User;
use App\Http\Controllers\Controller;
use App\Mail\PasswordResetVerificationCode;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

class AuthController extends Controller
{
    protected $table = 'password_resets_api';

    public function forgot(Request $request)
    {
        $data = $request->validate([
            'email' => 'required|email|exists:users,email',
        ]);
        $user = User::firstWhere('email', $data['email']);
        $token = random_int(10 ** 4, 10 ** 5 - 1);
        DB::table($this->table)->insert(['email' => $user->email, 'token' => $token]);
        Mail::to($user)->send(new PasswordResetVerificationCode($user, $token));
        return null;
    }

    public function verify(Request $request)
    {
        $data = $request->validate([
            'email' => 'required|email|exists:users,email',
            'token' => 'required|integer',
        ]);
        $row = DB::table($this->table)
            ->where('email', $data['email'])
            ->where('token', (int) $data['token'])
            ->whereRaw('created_at > current_timestamp - interval 5 minute')
            ->first();
        if ($row) {
            return null;
        } else {
            abort(403);
        }
    }

    public function reset(Request $request)
    {
        $data = $request->validate([
            'email' => 'required|email|exists:users,email',
            'token' => 'required|integer',
            'password' => 'required|string|min:8|confirmed',
        ]);
        $row = DB::table($this->table)
            ->where('email', $data['email'])
            ->where('token', (int) $data['token'])
            ->whereRaw('created_at > current_timestamp - interval 5 minute')
            ->first();
        if ($row) {
            $user = User::firstWhere('email', $data['email']);
            $user->password = Hash::make($data['password']);
            $user->save();
            return '';
        } else {
            abort(403);
        }
    }
}
