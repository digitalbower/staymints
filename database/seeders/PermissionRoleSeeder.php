<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PermissionRoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $now = now();

        $rolePermissions = array_map(function ($id) use ($now) {
            return [
                'permission_id' => $id,
                'role_id' => 1,
                'created_at' => $now,
                'updated_at' => $now,
            ];
        }, range(1, 25));

        DB::table('permission_roles')->insert($rolePermissions);
    }
}
