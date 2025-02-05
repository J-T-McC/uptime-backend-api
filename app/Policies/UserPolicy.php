<?php

namespace App\Policies;

use App\Enums\CrudAction;
use App\Enums\Role;
use App\Models\User;

class UserPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->canPerformCrudAction(CrudAction::INDEX, User::class);
    }

    public function view(User $user, User $User): bool
    {
        return $user->canPerformCrudAction(CrudAction::READ, User::class);
    }

    public function create(User $user): bool
    {
        return $user->canPerformCrudAction(CrudAction::CREATE, User::class);
    }

    public function update(User $user, User $User): bool
    {
        return $user->canPerformCrudAction(CrudAction::UPDATE, User::class);
    }

    public function delete(User $user, User $User): bool
    {
        return $user->canPerformCrudAction(CrudAction::DELETE, User::class);
    }

    public function attachAnyRole(User $user): bool
    {
        return $user->canPerformCrudAction(CrudAction::CREATE, Role::class);
    }

    public function attachRole(User $user): bool
    {
        return $user->canPerformCrudAction(CrudAction::CREATE, Role::class);
    }

    public function detachRole(User $user): bool
    {
        return $user->canPerformCrudAction(CrudAction::DELETE, Role::class);
    }
}
