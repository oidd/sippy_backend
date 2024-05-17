<?php

namespace App\Service;

use App\Models\Point;
use App\Models\User;
use Illuminate\Broadcasting\PrivateChannel;

class BroadcastingService
{
    public static function getUserPrivateChannel(User $user): PrivateChannel
    {
        return new PrivateChannel('private-' . $user->id);
    }

    public static function getPointsForUser(User $user): array
    {
        $points = Point::all();

        $res = [];

        foreach ($points as $i)
        {

        }
    }
}
