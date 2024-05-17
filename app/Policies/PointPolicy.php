<?php

namespace App\Policies;

use App\Models\Point;
use App\Models\User;

class PointPolicy
{
    public function read(User $user, Point $point): bool
    {
        return $point->shouldShowToUser($user) || $user->id === $point->user_id;
    }

    public function update(User $user, Point $point): bool
    {
        return $point->user_id === $user->id;
    }

    public function delete(User $user, Point $point): bool
    {
        return $point->user_id === $user->id;
    }
}
