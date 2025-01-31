<?php

namespace Database\Seeders;

use App\Models\Enums\Attributes\Description;
use App\Models\Enums\Attributes\DisplayName;
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
        /*
         * Create and associate non-crud permissions
         */
        foreach (\App\Models\Enums\Permission::cases() as $permission) {
            Permission::query()->updateOrCreate(
                attributes: [
                    'name' => $permission->value,
                ],
                values: [
                    'description' => $permission->getMeta(Description::class),
                    'display_name' => $permission->getMeta(DisplayName::class),
                ]
            );
        }

        $permissionMap = config('laratrust_seeder.role_permissions');

        foreach ($permissionMap as $roleName => $permissions) {
            $role = Role::query()->where('name', $roleName)->first();
            $permissions = Permission::query()->whereIn('name', $permissions)->pluck('id');
            $role->permissions()->sync($permissions);
        }

        /*
         * Create and associate CRUD permissions
         */
        $crudPermissionMap = config('laratrust_seeder.role_crud_permissions');
        $crudMap = collect(config('laratrust_seeder.crud_map'));

        foreach ($crudPermissionMap as $roleName => $crudPermissions) {
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
