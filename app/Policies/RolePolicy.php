<?php

namespace App\Policies;

use App\Models\User;
use Spatie\Permission\Models\Role;
use Illuminate\Auth\Access\HandlesAuthorization;

class RolePolicy
{
    use HandlesAuthorization;

    public function before($authUser)
    {

    }

    public function view(User $authUser, Role $role)
    {
        return isRoot() ?: abort(403, __('User does not have the right permissions.'));
    }


    public function update(User $authUser, Role $role)
    {

        return isRoot() ?: abort(403, __('User does not have the right permissions.'));
    }

    public function delete(User $authUser, Role $role)
    {

        return isRoot() ?: abort(403, __('User does not have the right permissions.'));

    }
}
