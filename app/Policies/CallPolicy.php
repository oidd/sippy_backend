<?php

namespace App\Policies;

use App\Models\Point;
use App\Models\Call;
use App\Models\User;

class CallPolicy
{
    public function send(User $user, Point $point)
    {
        if ($user->id === $point->user_id)
            return false;

        return $point->shouldShowToUser($user);
    }

    public function decide(User $user, Call $request)
    {
        return $user->id === $request->point()->get()->first()->user_id;
    }
}
