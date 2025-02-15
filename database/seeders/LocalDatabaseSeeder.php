<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

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
    }
}
