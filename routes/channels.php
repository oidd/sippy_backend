<?php

use App\Models\User;
use Illuminate\Support\Facades\Broadcast;

Broadcast::channel('messagechannel', function ($user) {
    return $user->id;
});
