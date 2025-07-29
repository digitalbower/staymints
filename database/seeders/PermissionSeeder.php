<?php

namespace Database\Seeders;

use App\Models\Permission;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $permissions = [
            'view_adminusers',
            'create_adminusers',
            'edit_adminusers',
            'delete_adminusers',
            'view_footer',
            'create_footer',
            'edit_footer',
            'delete_footer',
            'view_packages',
            'create_package',
            'edit_package',
            'delete_package',
            'changestatus_packages',
            'view_seo',
            'create_seo',
            'edit_seo',
            'view_categories',
            'create_category',
            'edit_category',
            'delete_category',
            'changestatus_category',
            'view_reviews',
            'reply_review',
            'delete_review',
            'view_active_leads',
            'assign_sales_person'
        ];
        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }
    }
}
