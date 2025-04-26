<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class RolesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create admin user
        User::create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'email_verified_at' => now(),
        ]);

        // Create teacher users
        User::create([
            'name' => 'Teacher One',
            'email' => 'teacher1@example.com',
            'password' => Hash::make('password'),
            'role' => 'teacher',
            'email_verified_at' => now(),
        ]);

        User::create([
            'name' => 'Teacher Two',
            'email' => 'teacher2@example.com',
            'password' => Hash::make('password'),
            'role' => 'teacher',
            'email_verified_at' => now(),
        ]);

        // Create student users
        for ($i = 1; $i <= 10; $i++) {
            User::create([
                'name' => 'Student ' . $i,
                'email' => 'student' . $i . '@example.com',
                'password' => Hash::make('password'),
                'role' => 'student',
                'email_verified_at' => now(),
            ]);
        }
    }
}
