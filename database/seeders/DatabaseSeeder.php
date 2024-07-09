<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        ini_set('max_execution_time', '300'); //300 seconds = 5 minutes
        ini_set('memory_limit', '1024M'); // for infinite time of execution

        $this->call(RolesSeeder::class);
        $this->call(UserSeeder::class);
        $this->call(AdminSeeder::class);
    }
}
