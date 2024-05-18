<?php

namespace App\Policies;

use App\Models\Point;
use App\Models\Request;
use App\Models\User;

class RequestPolicy
{
    public function sendRequest(User $user, Point $point)
    {
        if ($user->id === $point->user_id)
            return false;

        return $point->shouldShowToUser($user);
    }

    public function decideRequest(User $user, Request $request)
    {
        return $user->id === $request->point()->get()->user_id;
    }
}
