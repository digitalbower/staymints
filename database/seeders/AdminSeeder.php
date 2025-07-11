<?php

namespace Database\Seeders;

use App\Models\Admin;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        Admin::updateOrCreate(
            ['email' => 'superadmin@admin.com'], // Check if this email exists
            [
                'name' => 'Super Admin',
                'password' => Hash::make('admin123'), // Securely hash the password
                'email_verified_at' => now(),
            ]
        );
    

    }
}
