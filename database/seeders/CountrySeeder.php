<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CountrySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
       $countries = [
            ['country_name' => 'All over UAE', 'country_code' => 'UAE'],
            ['country_name' => 'Abu Dhabi', 'country_code' => 'AUH'],
            ['country_name' => 'Dubai', 'country_code' => 'DXB'],
            ['country_name' => 'Sharjah', 'country_code' => 'SHJ'],
            ['country_name' => 'Ajman', 'country_code' => 'AJM'],
            ['country_name' => 'Umm Al Quwain', 'country_code' => 'UAQ'],
            ['country_name' => 'Ras Al Khaimah', 'country_code' => 'RAK'],
            ['country_name' => 'Fujairah', 'country_code' => 'FUJ'],
        ];

        foreach ($countries as $country) {
            DB::table('countries')->updateOrInsert(
                ['country_code' => $country['country_code']],
                ['country_name' => $country['country_name']]
            );
        }
    }
}
