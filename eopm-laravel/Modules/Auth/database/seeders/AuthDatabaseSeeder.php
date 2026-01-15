<?php

namespace Modules\Auth\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Auth\Database\Seeders\CountryAndCitySeeder;

class AuthDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->command->info('Start seeding data!');
        $this->call([
            CountryAndCitySeeder::class,
            RoleAndPermissionSeeder::class,
            UserSeeder::class,
        ]);
        $this->command->info('Data seeding completed successfully!');
    }
}
