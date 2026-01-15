<?php

namespace Modules\Auth\Database\Seeders;

use Modules\Auth\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $admin = User::create([
            "name"=> "Admin User",
            "email"=> "admin1@example.com",
            "password"=> Hash::make('password123'),
            "phone"=> "+201234567890",
            "address"=> "14 Samir Sayed Ahmed",
            "country_id"=> 1,
            "city_id"=> 1,
            ]);
        $admin->assignRole('Administrator');
        $admin->save();

        $customer = User::create([
            "name"=> "Customer User",
            "email"=> "customer1@example.com",
            "password"=> Hash::make('password123'),
            "phone"=> "+966512345678",
            "address"=> "Prince Naif Rd, Al Bandriyah",
            "country_id"=> 2,
            "city_id"=> 6,
            ]);
        $customer->assignRole('Customer');
        $customer->save();
        $this->command->info('Users seeded successfully!');
    }
}
