<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use App\Models\User;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function getNotifications(Request $request)
    {
        $inp = $request->validate([
            'count' => 'required|integer|gt:0',
        ]);


        return User::notifications()->take($inp['count'])->get();
    }

    public function getUnreadNotificationsCount(Request $request)
    {

    }
}
