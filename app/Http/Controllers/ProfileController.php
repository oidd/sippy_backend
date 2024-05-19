<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

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

    public function updateMyProfile(Request $request)
    {
        $inp = $request->validate([
            'name' => 'string',
            'about_me' => 'nullable|string'
        ]);

        $request->user()->update($inp);

        return $request->user();
    }

//    public function updateProfileAvatar(Request $request)
//    {
//        $inp = $request->validate([
//            'photo' => 'file|required'
//        ]);
//
//        Storage::disk('local')->put('avatars/' . $request->user()->id . '.jpg', $inp);
//    }
}
