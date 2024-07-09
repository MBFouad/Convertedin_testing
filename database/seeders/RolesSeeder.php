<?php

namespace Database\Seeders;

use App\Models\User;
use App\Utilities\Constants;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class RolesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        if (!Role::where('name', Constants::USER_ROLE['Admin'])->first()) {
            $role = Role::create(['name' => Constants::USER_ROLE['Admin']]);
        }
        if (!Role::where('name', Constants::USER_ROLE['USER'])->first()) {
            $role = Role::create(['name' => Constants::USER_ROLE['USER']]);
        }
    }
}
