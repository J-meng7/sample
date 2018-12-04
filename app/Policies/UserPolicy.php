<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class UserPolicy
{
    use HandlesAuthorization;

    /**
     * Create a new policy instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    public function update(User $currentUserm,User $user)
    {
        return $currentUserm->id === $user->id;
    }

    public function destroy(User $currentUserm,User $user)
    {
        return $currentUserm->is_admin && $currentUserm->id !== $user->id;
    }
}
