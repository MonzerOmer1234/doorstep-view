<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Amenity;
use Faker\Factory as Faker;

class AmenitySeeder extends Seeder
{
    public function run()
    {
        $faker = Faker::create();

        for ($i = 0; $i < 100; $i++) {
            Amenity::create([
                'name' => $faker->word(),
                'icon' => 'icon_' . $i . '.png', // Assuming icons are named icon_0.png, icon_1.png, etc.
                'category' => $faker->randomElement(['school', 'supermarket', 'hospital', 'gym', 'restaurant']),
                'address' => $faker->address(),
                'latitude' => $faker->latitude(),
                'longitude' => $faker->longitude(),
            ]);
        }
    }
}
