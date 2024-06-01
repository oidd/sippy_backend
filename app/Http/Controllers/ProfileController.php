<?php

namespace App\Http\Controllers;

use App\Http\Requests\Profile\updateRequest;
use App\Models\User;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
    public function getMyProfile(Request $request)
    {
        return response()->json($request->user());
    }

    public function getProfile(User $user)
    {
        return response()->json($user);
    }

    public function updateMyProfile(updateRequest $request)
    {
        return response()->json($request->user()->update($request->validated()));
    }
}
