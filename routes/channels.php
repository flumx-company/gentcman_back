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

Broadcast::channel('Gentcmen.Models.User.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});

Broadcast::channel('answer-no-found', function () {
    return true;
});

Broadcast::channel('report-problem', function () {
    return true;
});

Broadcast::channel('mark-all-notifications-as-read', function () {
    return true;
});

Broadcast::channel('notification-removed', function () {
    return true;
});

Broadcast::channel('post-offer', function () {
    return true;
});

Broadcast::channel('development-idea', function () {
    return true;
});
Broadcast::channel('improvement-idea', function () {
    return true;
});

Broadcast::channel('user-placed-order', function () {
    return true;
});