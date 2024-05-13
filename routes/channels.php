<?php

use Illuminate\Support\Facades\Broadcast;
use Illuminate\Support\Facades\Log;

Broadcast::channel('flights', function () {
  return true;
});

Broadcast::channel('flights-private.{userId}', function ($user, $userId) {
    if (empty($user)) {
        return false;
    }

    Log::debug($user->id);

    return (int) $user->id === (int) $userId;
});
