<?php

namespace App\Policies;

use App\Enums\CrudAction;
use App\Models\Monitor;
use App\Models\User;

class MonitorPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->canPerformCrudAction(CrudAction::INDEX, Monitor::class);
    }

    public function view(User $user, Monitor $monitor): bool
    {
        return $user->canPerformCrudAction(CrudAction::READ, Monitor::class);
    }

    public function create(User $user): bool
    {
        return $user->canPerformCrudAction(CrudAction::CREATE, Monitor::class);
    }

    public function update(User $user, Monitor $monitor): bool
    {
        return $user->canPerformCrudAction(CrudAction::UPDATE, Monitor::class);
    }

    public function delete(User $user, Monitor $monitor): bool
    {
        return $user->canPerformCrudAction(CrudAction::DELETE, Monitor::class);
    }
}
