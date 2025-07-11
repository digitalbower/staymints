<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PermissionAdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $now = now();

        $adminPermissions = array_map(function ($id) use ($now) {
            return [
                'permission_id' => $id,
                'admin_id' => 1,
                'created_at' => $now,
                'updated_at' => $now,
            ];
        }, range(1, 16));

        DB::table('permission_admins')->insert($adminPermissions);
    }
}
