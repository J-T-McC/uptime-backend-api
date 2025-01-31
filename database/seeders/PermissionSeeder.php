<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\Role;
use Illuminate\Database\Seeder;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $permissionMap = config('laratrust_seeder.role_crud_permissions');
        $crudMap = collect(config('laratrust_seeder.crud_map'));

        foreach ($permissionMap as $roleName => $crudPermissions) {

            $role = Role::query()->where('name', $roleName)->first();
            $permissionsToSync = [];

            foreach ($crudPermissions as $table => $permissions) {
                foreach (explode(',', $permissions) as $permission) {
                    $permissionValue = $crudMap->get($permission);

                    $permissionsToSync[] = Permission::query()->updateOrCreate([
                        'name' => $table.'-'.$permissionValue,
                        'display_name' => trim(ucfirst($permissionValue).' '.ucwords(str_replace('-', ' ', $table))),
                        'description' => trim(ucfirst($permissionValue).' '.ucwords(str_replace('-', ' ', $table))),
                    ])->id;
                }
            }

            $role->permissions()->sync($permissionsToSync);
        }
    }
}
