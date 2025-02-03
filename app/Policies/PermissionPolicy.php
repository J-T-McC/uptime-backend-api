<?php

namespace App\Policies;

use App\Enums\CrudAction;
use App\Models\Permission;
use App\Models\User;

class PermissionPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->canPerformCrudAction(CrudAction::INDEX, Permission::class);
    }

    public function view(User $user, User $User): bool
    {
        return $user->canPerformCrudAction(CrudAction::READ, Permission::class);
    }

    public function create(User $user): bool
    {
        return $user->canPerformCrudAction(CrudAction::CREATE, Permission::class);
    }

    public function update(User $user, Permission $User): bool
    {
        return $user->canPerformCrudAction(CrudAction::UPDATE, Permission::class);
    }

    public function delete(User $user, Permission $User): bool
    {
        return $user->canPerformCrudAction(CrudAction::DELETE, Permission::class);
    }
}
