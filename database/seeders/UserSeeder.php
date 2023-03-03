<?php

namespace Database\Seeders;

use App\Enums\UserType;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('users')->insert([
            'first_name' => 'Admin',
            'last_name' => 'Mactavis',
            'email' => 'adminmactavis@gmail.com',
            'password' => Hash::make('adminmactavis'),
            'phone_number' => '07034548022',
            'role' => UserType::CUSTOMER->value,
            'active' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
