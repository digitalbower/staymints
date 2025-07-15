<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UnitTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
         $unitTypes = [
            ['type_name' => 'Person', 'status' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['type_name' => 'Pack', 'status' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['type_name' => 'Group', 'status' => 1, 'created_at' => now(), 'updated_at' => now()],
        ];

        
        foreach ($unitTypes as $unitType) {
            DB::table('unit_types')->updateOrInsert(
                ['type_name' => $unitType['type_name']], 
                $unitType
            );
        }
    }
}
