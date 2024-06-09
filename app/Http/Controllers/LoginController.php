<?php

namespace App\Http\Controllers;

use App\Http\Requests\Auth\loginRequest;
use App\Http\Requests\Auth\registerRequest;
use App\Models\User;
use Firebase\JWT\JWT;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function register(registerRequest $request)
    {
        return response()->json(User::create($request->validated()));
    }

    public function login(loginRequest $request)
    {
        $inp = $request->validated();

        if (! Auth::attempt([
            'email' => $inp['email'],
            'password' => md5($inp['password']),
        ]))
            throw new AuthenticationException('Bad credentials.');

        $payload = [
            'user_id' => User::where('email', $inp['email'])->first()->id,
        ];

        return response()->json(JWT::encode($payload, config('jwt.secret'), 'HS256'));
    }
}
