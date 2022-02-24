<?php

use Illuminate\Support\Facades\Broadcast;

/*
|--------------------------------------------------------------------------
| Broadcast Channels
|--------------------------------------------------------------------------
|
| Here you may register all of the event broadcasting channels that your
| application supports. The given channel authorization callbacks are
| used to check if an authenticated user can listen to the channel.
|
 */

Broadcast::channel('user.{id}', function ($user, $id) {
    return true;
});

Broadcast::channel('dashboard', function () {
    return true;
});

Broadcast::channel('logging', function () {
    return true;
});

Broadcast::channel('course', function ($user) {
    return true;
});

Broadcast::channel('schedule', function ($user) {
    return true;
});

Broadcast::channel('classroom', function ($user) {
    return true;
});
