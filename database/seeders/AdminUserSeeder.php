<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'administrator@gmail.com'], // avoid duplicates
            [
                'name' => 'Administrator',
                'role' => 'admin',
                'password' => Hash::make('admin123'), // secure password
                'email_verified_at' => now(),
            ]
        );

        User::updateOrCreate(
            ['email' => 'user@gmail.com'], // avoid duplicates
            [
                'name' => 'User',
                'role' => 'user',
                'password' => Hash::make('user123'), // secure password
                'email_verified_at' => now(),
            ]
        );

        User::updateOrCreate(
            ['email' => 'viewer@gmail.com'], // avoid duplicates
            [
                'name' => 'Viewer',
                'role' => 'viewer',
                'password' => Hash::make('default123'), // secure password
                'email_verified_at' => now(),
            ]
        );
    }
}
