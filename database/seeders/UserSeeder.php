<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Admin Account
        User::create([
            'name' => 'Admin NexRig',
            'email' => 'admin@nexrig.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
        ]);

        // Customer Account
        User::create([
            'name' => 'Customer Demo',
            'email' => 'user@gmail.com',
            'password' => Hash::make('password'),
            'role' => 'user',
        ]);
    }
}