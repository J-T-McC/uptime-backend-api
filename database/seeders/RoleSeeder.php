<?php

namespace Database\Seeders;

use App\Models\Enums\Attributes\Description;
use App\Models\Enums\Role;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
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
                    'display_name' => $role->getMeta(Description::class),
                ]
            );
        }
    }
}
