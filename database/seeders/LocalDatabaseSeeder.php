<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Artisan;

class LocalDatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            DatabaseSeeder::class,
            UsersTableSeeder::class,
            MonitorTableSeeder::class,
            ChannelTableSeeder::class,
            MonitorEventTableSeeder::class,
            MonitorUptimeEventCountTableSeeder::class,
        ]);

        Artisan::call('admin:assign-role-to-user --role-id=1 --user-id=1');
    }
}
