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
                'password' => Hash::make('admin123'), // secure password
                'email_verified_at' => now(),
            ]
        );
    }
}
