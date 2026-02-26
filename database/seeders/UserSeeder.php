<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        User::firstOrCreate(
            ['email' => env('ADMIN_EMAIL', 'admin@nexrig.com')],
            [
                'name' => 'Admin NexRig',
                'password' => Hash::make(env('ADMIN_PASSWORD', 'password')),
                'role' => 'admin',
            ]
        );

        User::firstOrCreate(
            ['email' => env('DEMO_USER_EMAIL', 'user@gmail.com')],
            [
                'name' => 'Customer Demo',
                'password' => Hash::make(env('DEMO_USER_PASSWORD', 'password')),
                'role' => 'user',
            ]
        );
    }
}