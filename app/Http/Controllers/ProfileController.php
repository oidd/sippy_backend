<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
    public function getMyProfile(Request $request)
    {
        return $request->user();
    }

    public function getProfile(int $id, Request $request)
    {
        return User::find($id);
    }
}
