<?php

namespace App\Policies;

use App\Enums\CrudAction;
use App\Models\Channel;
use App\Models\User;

class ChannelPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->canPerformCrudAction(CrudAction::INDEX, Channel::class);
    }

    public function view(User $user, Channel $Channel): bool
    {
        return $user->canPerformCrudAction(CrudAction::READ, Channel::class);
    }

    public function create(User $user): bool
    {
        return $user->canPerformCrudAction(CrudAction::CREATE, Channel::class);
    }

    public function update(User $user, Channel $Channel): bool
    {
        return $user->canPerformCrudAction(CrudAction::UPDATE, Channel::class);
    }

    public function delete(User $user, Channel $Channel): bool
    {
        return $user->canPerformCrudAction(CrudAction::DELETE, Channel::class);
    }
}
