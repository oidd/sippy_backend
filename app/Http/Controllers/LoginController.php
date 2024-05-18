<?php

namespace App\Http\Controllers;

use App\Models\User;
use Firebase\JWT\JWT;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class LoginController extends Controller
{
    public function register(Request $request)
    {
        $inp = $request->validate([
            'name' => 'required|string|unique:users',
            'email' => 'required|string|email|unique:users',
            'password' => 'required|string',
            'gender' => 'required|boolean',
            'age' => 'required|integer|between:16,100',
            'about_me' => 'string'
        ]);

        return response()->json(User::create([
            'name' => $inp['name'],
            'email' => $inp['email'],
            'password' => md5($inp['password']),
            'gender' => $inp['gender'],
            'age' => $inp['age'],
            'about_me' => $inp['about_me'] ?? null,
        ]));
    }

    public function login(Request $request)
    {
        $request->validate(
            [
                'email' => 'required|email',
                'password' => 'required'
            ]
        );

        if (!empty($u = DB::table('users')->where('email', $request->input('email'))->first())
            && md5($request->input('password')) == $u->password)
        {
            $payload = [
                'user_id' => $u->id,
            ];

            return response()->json(JWT::encode($payload, env('AUTH_JWT_SECRET_KEY'), 'HS256'));
        }

        return response()->json(
            [
                'message' => 'bad login',
                'errors' => ['login' => ['password and login doesn\'t match']]
            ]
        );
    }
}
