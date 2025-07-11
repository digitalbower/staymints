<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Permission;
use App\Models\PermissionAdmin;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {   
        $this->call([
            RoleSeeder::class,
            AdminSeeder::class,
            CountrySeeder::class,
            TagSeeder::class,
            CategorySeeder::class,
            UnitTypeSeeder::class,
            PermissionSeeder::class,
            PermissionAdminSeeder::class
        ]);
    }
}
