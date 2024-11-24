<?php

namespace Database\Seeders;

<<<<<<< HEAD
use App\Models\Amenity;
use Illuminate\Database\Seeder;
=======
use Illuminate\Database\Seeder;
use App\Models\Amenity;
use Faker\Factory as Faker;
>>>>>>> a6659e284f56c77e1a7ee2ac560a00302d5c39d4

class AmenitySeeder extends Seeder
{
    public function run()
    {
<<<<<<< HEAD
        // Create 100 random amenities
        Amenity::factory()->count(100)->create();
=======
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
>>>>>>> a6659e284f56c77e1a7ee2ac560a00302d5c39d4
    }
}
