<?php

namespace App\Http\Controllers;

use App\Models\User;
use Firebase\JWT\JWT;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class LoginController extends Controller
{
    public function register(Request $request)
    {
        $inp = $request->validate([
            'name' => 'required|string',
            'email' => 'required|string',
            'password' => 'required|string'
        ]);

        return response()->json(User::create($inp));
    }

    public function login(Request $request)
    {
        $request->validate(
            [
                'name' => 'required',
                'password' => 'required'
            ]
        );

        if (!empty($u = DB::table('users')->where('name', $request->input('name'))->first())
            && md5($request->input('password')) == $u->password)
        {
            $payload = [
                'user_id' => $u->id,
            ];

            return response()->json(JWT::encode($payload, env('AUTH_JWT_SECRET_KEY'), 'HS256'));
        }

//
//        if (($u = User::find(['name' => $request->input('login')]))->password
//                == md5($request->input('password'))) {
//            $payload = [
//                'user_id' => $u->id,
//            ];
//
//            return response()->json(JWT::encode($payload, env('AUTH_JWT_SECRET_KEY'), 'HS256'));
//        }

        return response()->json(['error' => 'Bad login'], 401);
    }
}
