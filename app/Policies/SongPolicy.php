<?php

namespace App\Policies;

use App\Models\Song;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class SongPolicy
{
    use HandlesAuthorization;


    /**
     * Determines whether a user can download a song
     *
     * @param  User  $user
     * @param  Song  $song
     * @return bool
     */
    public function download(User $user, Song $song)
    {
        return ($user->id === $song->album->user_id) || $user->isAdmin();
    }

    public function update(User $user, Song $song)
    {
        return ($user->id === $song->album->user_id) || $user->isAdmin();
    }
}
