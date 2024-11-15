<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;

class PropertiesTableSeeder extends Seeder
{
    public function run()
    {
        $faker = Faker::create();

        // List of Middle Eastern countries
        $locations = [
            'Saudi Arabia', 'UAE', 'Qatar', 'Kuwait', 'Bahrain',
            'Oman', 'Jordan', 'Lebanon', 'Iraq', 'Syria',
            'Egypt', 'Turkey', 'Yemen', 'Palestine', 'Iran'
        ];

        $propertyTypes = ['apartment', 'villa', 'townhouse', 'duplex'];

        for ($i = 0; $i < 100; $i++) {
            DB::table('properties')->insert([
                'user_id' => 1, // Assuming a user with ID 1 exists
                'title' => $faker->sentence(3),
                'description' => $faker->paragraph(2),
                'price' => $faker->randomFloat(2, 10000, 1000000),
                'location' => $faker->randomElement($locations),
                'bedrooms' => $faker->numberBetween(1, 5),
                'bathrooms' => $faker->numberBetween(1, 4),
                'area' => $faker->numberBetween(500, 5000), // Area in square feet
                'property_type' => $faker->randomElement($propertyTypes),
                'status' => 'available', // All properties set to available
                'views' => $faker->numberBetween(0, 100),
                'latitude' => $faker->latitude,
                'longitude' => $faker->longitude,
                'neighborhood' => $faker->word,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
