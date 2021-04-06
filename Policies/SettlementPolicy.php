<?php

namespace Mohsen\Payment\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;
use Mohsen\RolePermissions\Models\Permission;
use Mohsen\User\Models\User;

class SettlementPolicy
{
    use HandlesAuthorization;

    public function manage($user)
    {
        return $user->hasPermissionTo(Permission::PERMISSION_MANAGE_SETTLEMENTS);
   }

    public function store($user)
    {
        return $user->hasPermissionTo(Permission::PERMISSION_TEACH);
   }
}
