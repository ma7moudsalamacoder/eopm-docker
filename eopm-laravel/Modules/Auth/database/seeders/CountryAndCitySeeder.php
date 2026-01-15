<?php

namespace Modules\Auth\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CountryAndCitySeeder extends Seeder
{
/**
     * Run the database seeds.
     */
    public function run(): void
    {
         
         DB::transaction(function () {
            // 1. Define and insert countries
            $countries = [
                'Egypt',
                'Saudi Arabia',
                'United Arab Emirates',
                'Jordan',
                'Lebanon',
            ];

            foreach ($countries as $country) {
                DB::table('countries')->insert([
                    'name' => $country,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }

            $countryMap = DB::table('countries')->pluck('id', 'name');
            $cities = [
                ['name' => 'Cairo', 'country_name' => 'Egypt'],
                ['name' => 'Alexandria', 'country_name' => 'Egypt'],
                ['name' => 'Giza', 'country_name' => 'Egypt'],
                ['name' => 'Shubra El-Kheima', 'country_name' => 'Egypt'],
                ['name' => 'Port Said', 'country_name' => 'Egypt'],
                
                ['name' => 'Riyadh', 'country_name' => 'Saudi Arabia'],
                ['name' => 'Jeddah', 'country_name' => 'Saudi Arabia'],
                ['name' => 'Mecca', 'country_name' => 'Saudi Arabia'],
                ['name' => 'Medina', 'country_name' => 'Saudi Arabia'],
                ['name' => 'Dammam', 'country_name' => 'Saudi Arabia'],
                
                ['name' => 'Dubai', 'country_name' => 'United Arab Emirates'],
                ['name' => 'Abu Dhabi', 'country_name' => 'United Arab Emirates'],
                ['name' => 'Sharjah', 'country_name' => 'United Arab Emirates'],
                ['name' => 'Al Ain', 'country_name' => 'United Arab Emirates'],
                ['name' => 'Ajman', 'country_name' => 'United Arab Emirates'],
                
                ['name' => 'Amman', 'country_name' => 'Jordan'],
                ['name' => 'Zarqa', 'country_name' => 'Jordan'],
                ['name' => 'Irbid', 'country_name' => 'Jordan'],
                ['name' => 'Aqaba', 'country_name' => 'Jordan'],
                ['name' => 'Madaba', 'country_name' => 'Jordan'],
                
                ['name' => 'Beirut', 'country_name' => 'Lebanon'],
                ['name' => 'Tripoli', 'country_name' => 'Lebanon'],
                ['name' => 'Sidon', 'country_name' => 'Lebanon'],
                ['name' => 'Tyre', 'country_name' => 'Lebanon'],
                ['name' => 'Jounieh', 'country_name' => 'Lebanon'],
            ];
            foreach ($cities as $city) {
                DB::table('cities')->insert([
                    'name' => $city['name'],
                    'country_id' => $countryMap->get($city['country_name']),
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        });

        $this->command->info('Countries and cities seeded successfully!');
    
    }
}
