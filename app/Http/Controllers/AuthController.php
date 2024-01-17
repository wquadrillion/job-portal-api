<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Models\User;
use App\Traits\HttpResponses;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use \App\Http\Requests\RegisterRequest;
use \App\Http\Requests\ForgotPasswordRequest;
use Illuminate\Support\Facades\Password;


class AuthController extends Controller
{
    use HttpResponses;

    public function login(LoginRequest $request)
    {
        $request->validated($request->only(['email', 'password']));

        if(!Auth::attempt($request->only(['email', 'password']))) {
            return $this->error('', 'Credentials do not match', 401);
        }

        $user = User::where('email', $request->email)->first();

        return $this->success([
            'user' => $user,
            'token' => $user->createToken('API Token')->plainTextToken
        ]);
    }

    public function register(RegisterRequest $request)
    {
        //$request->validated($request->only(['first_name','last_name','email', 'password']));

        $user = User::create([
            'first_name' =>$request->first_name,
            'last_name' => $request->last_name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        return $this->success([
            'user' => $user,
            'token' => $user->createToken('API Token')->plainTextToken
        ]);
    }

    public function forgot(ForgotPasswordRequest $request)
    {
        if(User::where('email', $request->email)->first()){
            $status = Password::sendResetLink(
                $request->only('email')
            );
        }


        return $this->success([
            'message' => 'If you have an account with us, Mail Sent'
        ]);
    }
    public function logout()
    {
        Auth::user()->currentAccessToken()->delete();

        return $this->success([
            'message' => 'You have successfully been logged out and your token has been removed'
        ]);
    }
}