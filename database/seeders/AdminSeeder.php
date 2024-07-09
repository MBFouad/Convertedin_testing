<?php

namespace Database\Seeders;

use App\Utilities\Constants;
use Illuminate\Database\Seeder;
use App\Models\User;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::factory()->withRole(Constants::USER_ROLE['Admin'])->count(100)->create();
    }
}
