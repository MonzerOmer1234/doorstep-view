<?php

namespace Database\Seeders;
use App\Models\Apartment;
use Faker\Factory as Faker;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ApartmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create();

        for ($i = 0; $i < 100; $i++) {
            Apartment::create([
                'user_id' => 1, // Assuming you have a user with ID 1
                'title' => $faker->sentence(3),
                'description' => $faker->paragraph,
                'price' => $faker->numberBetween(50000, 500000),
                'address' => $faker->address,
                'available' => $faker->boolean(70), // 70% chance to be available
                'rooms' => $faker->numberBetween(1, 5),
                'area' => $faker->numberBetween(30, 150), // in square meters
                'building_age' => $faker->numberBetween(0, 50), // Age of the building
                'neighborhood_id' => 1
            ]);
        }
    }
}
