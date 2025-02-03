<?php

namespace App\Console\Commands;

use App\Models\Role;
use App\Models\User;
use Illuminate\Console\Command;

class AssignRoleToUser extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'admin:assign-role-to-user { --role-id= : The ID of the role } { --user-id= : The ID of the user }';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Assign a role to a user';

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        $roleId = $this->option('role-id');
        $userId = $this->option('user-id');

        $role = Role::query()->findOrFail($roleId);
        $user = User::query()->findOrFail($userId);

        $user->addRole($role);
    }
}
