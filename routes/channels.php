<?php

use App\Models\StandUpGroup;
use App\Models\User;
use Illuminate\Support\Facades\Broadcast;

Broadcast::channel('App.Models.User.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});

Broadcast::channel('stand-up.{id}', function (User $user, $id) {
    return $user->allTeams()->contains(StandUpGroup::find($id)->team);
});

