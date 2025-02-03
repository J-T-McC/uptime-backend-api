<?php

namespace Database\Seeders;

use App\Enums\Attributes\Description;
use App\Enums\Attributes\DisplayName;
use App\Enums\Role;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        foreach (Role::cases() as $role) {
            \App\Models\Role::query()->updateOrCreate(
                attributes: [
                    'name' => $role->value,
                ],
                values: [
                    'description' => $role->getMeta(Description::class),
                    'display_name' => $role->getMeta(DisplayName::class),
                ]
            );
        }
    }
}
