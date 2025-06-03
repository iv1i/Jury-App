<?php

use Illuminate\Support\Facades\Auth;
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

//Broadcast::channel('App.Models.User.{id}', function ($user, $id) {
//    return (int) $user->id === (int) $id;
//});


Broadcast::channel('channel-admin-teams', function ($user) {
    return Auth::guard('admin')->check();
}, ['guards' => ['web', 'admin']]);

Broadcast::channel('channel-admin-tasks', function ($user) {
    return Auth::guard('admin')->check();
}, ['guards' => ['web', 'admin']]);

Broadcast::channel('channel-admin-home', function ($user) {
    return Auth::guard('admin')->check();
}, ['guards' => ['web', 'admin']]);

Broadcast::channel('channel-admin-scoreboard', function ($user) {
    return Auth::guard('admin')->check();
}, ['guards' => ['web', 'admin']]);


Broadcast::channel('channel-app-scoreboard', function ($user) {
    return \auth()->check();
}, ['guards' => ['web']]);
Broadcast::channel('channel-app-statistic', function ($user) {
    return \auth()->check();
}, ['guards' => ['web']]);
Broadcast::channel('channel-app-statisticID', function ($user) {
    return \auth()->check();
}, ['guards' => ['web']]);
Broadcast::channel('channel-app-home', function ($user) {
    return \auth()->check();
}, ['guards' => ['web']]);
Broadcast::channel('channel-app-checktask.{Id}', function ($user, int $Id) {
    return \auth()->check() && $user->id === $Id;
}, ['guards' => ['web']]);
