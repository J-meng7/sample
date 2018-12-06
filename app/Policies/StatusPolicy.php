<?php

namespace App\Policies;

use App\Models\Status;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class StatusPolicy
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

    /**授权策略来对用户进行授权删除的操作，只有当被删除的微博作者为当前用户，授权才能通过
     * @param User $user
     * @param Status $status
     * @return bool
     */
    public function destroy(User $user,Status $status)
    {
        return $user->id === $status->user_id;
    }
}
