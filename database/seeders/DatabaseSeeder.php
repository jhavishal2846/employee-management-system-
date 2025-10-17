<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Shift;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create admin user
        User::create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'status' => 'active',
            'department' => 'Management',
            'hourly_rate' => 25.00,
        ]);

        // Create employee users
        User::create([
            'name' => 'John Doe',
            'email' => 'employee1@example.com',
            'password' => Hash::make('password'),
            'role' => 'employee',
            'status' => 'active',
            'department' => 'Operations',
            'hourly_rate' => 15.00,
        ]);

        User::create([
            'name' => 'Jane Smith',
            'email' => 'employee2@example.com',
            'password' => Hash::make('password'),
            'role' => 'employee',
            'status' => 'active',
            'department' => 'Operations',
            'hourly_rate' => 15.00,
        ]);

        // Create sample shifts
        Shift::create([
            'shift_name' => 'Morning Shift',
            'shift_type' => 'morning',
            'start_time' => '08:00:00',
            'end_time' => '16:00:00',
            'max_capacity' => 5,
            'description' => 'Standard morning shift',
            'location' => 'Main Office',
            'status' => 'active',
        ]);

        Shift::create([
            'shift_name' => 'Evening Shift',
            'shift_type' => 'evening',
            'start_time' => '16:00:00',
            'end_time' => '00:00:00',
            'max_capacity' => 3,
            'description' => 'Standard evening shift',
            'location' => 'Main Office',
            'status' => 'active',
        ]);

        Shift::create([
            'shift_name' => 'Night Shift',
            'shift_type' => 'night',
            'start_time' => '00:00:00',
            'end_time' => '08:00:00',
            'max_capacity' => 2,
            'description' => 'Standard night shift',
            'location' => 'Main Office',
            'status' => 'active',
        ]);
    }
}
