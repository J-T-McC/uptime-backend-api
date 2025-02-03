<?php

namespace App\Policies;

use App\Enums\CrudAction;
use App\Models\Role;
use App\Models\User;

class RolePolicy
{
    public function viewAny(User $user): bool
    {
        return $user->canPerformCrudAction(CrudAction::INDEX, Role::class);
    }

    public function view(User $user, User $User): bool
    {
        return $user->canPerformCrudAction(CrudAction::READ, Role::class);
    }

    public function create(User $user): bool
    {
        return $user->canPerformCrudAction(CrudAction::CREATE, Role::class);
    }

    public function update(User $user, Role $User): bool
    {
        return $user->canPerformCrudAction(CrudAction::UPDATE, Role::class);
    }

    public function delete(User $user, Role $User): bool
    {
        return $user->canPerformCrudAction(CrudAction::DELETE, Role::class);
    }

    public function attachAnyPermission(User $user): bool
    {
        return $user->canPerformCrudAction(CrudAction::CREATE, Role::class);
    }

    public function attachPermission(User $user): bool
    {
        return $user->canPerformCrudAction(CrudAction::CREATE, Role::class);
    }

    public function detachPermission(User $user): bool
    {
        return $user->canPerformCrudAction(CrudAction::DELETE, Role::class);
    }
}
