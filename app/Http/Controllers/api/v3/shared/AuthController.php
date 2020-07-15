<?php

namespace App\Http\Controllers\api\v3\shared;

use App\User;
use App\Http\Controllers\Controller;
use App\Mail\PasswordResetVerificationCode;
use Carbon\Carbon;
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
        $user = $this->validateUser($request);
        $token = random_int(10 ** 4, 10 ** 5 - 1);
        DB::table($this->table)->insert(['email' => $user->email, 'token' => $token]);
        Mail::to($user)->send(new PasswordResetVerificationCode($user, $token));
        return null;
    }

    public function verify(Request $request)
    {
        $this->validateUser($request);
        $this->validateToken($request);
        return null;
    }

    public function reset(Request $request)
    {
        $user = $this->validateUser($request);
        $this->validateToken($request, 10);
        $data = $request->validate([
            'password' => 'required|string|min:8|confirmed',
        ]);
        $user->password = Hash::make($data['password']);
        $user->save();
        return null;
    }

    public function validateUser(Request $request)
    {
        $email = $request->validate([
            'email' => 'required|email',
        ])['email'];
        $user = User::firstWhere('email', $email);
        if ($user) {
            return $user;
        } else {
            abort(403, 'User not found');
        }
    }

    public function validateToken(Request $request, int $minutes = 5)
    {
        $data = $request->validate([
            'email' => 'required|email',
            'token' => 'required|integer',
        ]);
        $row = DB::table($this->table)
            ->where('email', $data['email'])
            ->where('token', $data['token'])
            ->first();
        if (!$row) {
            abort(403, 'Token not found');
        }
        $created_at = new Carbon($row->created_at);
        $minutes = max(0, min(10, $minutes));
        if (now()->isAfter($created_at->addMinutes($minutes))) {
            abort(403, 'Token expired');
        }
    }
}
