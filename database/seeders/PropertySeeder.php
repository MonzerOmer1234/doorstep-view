<?php

namespace Database\Seeders;

use App\Models\Property;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;

class PropertySeeder extends Seeder
{
    public function run()
    {
        // Central coordinates (Dubai, UAE)
        $central_lat = 25.276987;
        $central_lon = 55.296249;
        $locations = [
            'Saudi Arabia', 'UAE', 'Qatar', 'Kuwait', 'Bahrain',
            'Oman', 'Jordan', 'Lebanon', 'Iraq', 'Syria',
            'Egypt', 'Turkey', 'Yemen', 'Palestine', 'Iran'
        ];

        // Create an instance of Faker to generate random data
        $faker = Faker::create();

        // Generate 100 properties with nearby coordinates
        for ($i = 0; $i < 100; $i++) {
            // Generate a random latitude and longitude variation (within a very small range)
            $latitude = $central_lat + mt_rand(-100, 100) / 1000; // Random within ±0.1 degrees
            $longitude = $central_lon + mt_rand(-100, 100) / 1000; // Random within ±0.1 degrees

            // Create a new property and save it to the database
            Property::create([
                'user_id' => 1, // Assuming you want to assign the property to user_id 1
                'title' => $faker->word() . ' Property', // Random title
                'description' => $faker->text(200), // Random description
                'price' => $faker->randomFloat(2, 100000, 500000), // Random price between 100,000 and 500,000
                'location' => $faker->randomElement($locations),// Random city name
                'bedrooms' => $faker->numberBetween(1, 5), // Random number of bedrooms
                'bathrooms' => $faker->numberBetween(1, 5), // Random number of bathrooms
                'area' => $faker->numberBetween(500, 5000), // Random area between 500 and 5000 sq ft
                'property_type' => $faker->randomElement(['apartment', 'villa', 'house', 'studio']), // Random property type
                'status' => $faker->randomElement(['available', 'sold']), // Random status
                 
                'latitude' => $latitude, // Generated latitude
                'longitude' => $longitude, // Generated longitude
                'neighborhood' => $faker->word(), // Random neighborhood name
            ]);
        }
    }
}
