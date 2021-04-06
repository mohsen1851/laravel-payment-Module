<?php


namespace Mohsen\Payment\Policies;


use Illuminate\Auth\Access\HandlesAuthorization;
use Mohsen\RolePermissions\Models\Permission;

class PaymentPolicy
{
    use HandlesAuthorization;

    public function manage($user)
    {
        return $user->hasPermissionTo(Permission::PERMISSION_MANAGE_PAYMENTS);
}

}
