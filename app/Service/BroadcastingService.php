<?php

namespace App\Service;

use App\Models\Point;
use App\Models\User;
use Illuminate\Broadcasting\PrivateChannel;
use Pusher\Pusher;

class BroadcastingService
{
    public static function getOnlineUsers()
    {
        new Pusher();
    }
}
