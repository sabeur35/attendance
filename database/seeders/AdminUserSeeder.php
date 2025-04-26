<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create admin user with specified credentials
        User::create([
            'name' => 'Sabeur Admin',
            'email' => 'sabeur35@gmail.com',
            'password' => Hash::make('0123456789'),
            'role' => 'admin',
            'email_verified_at' => now(),
        ]);

        // Output confirmation message
        $this->command->info('Admin user created successfully!');
    }
}
