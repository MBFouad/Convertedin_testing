<?php

namespace Database\Seeders;

use App\Models\User;
use App\Utilities\Constants;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::factory()->withRole(Constants::USER_ROLE['USER'])->count(1000)->create();
    }
}
