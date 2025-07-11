<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TagSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $tags = [
            ['tag_name' => 'Popular', 'created_at' => now(), 'updated_at' => now()],
            ['tag_name' => 'Featured', 'created_at' => now(), 'updated_at' => now()],
        ];

        foreach ($tags as $tag) {
            DB::table('tags')->updateOrInsert(
                ['tag_name' => $tag['tag_name']], 
                $tag                               
            );
        }
    }
}
