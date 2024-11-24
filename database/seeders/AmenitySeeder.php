<?php

namespace Database\Seeders;

use App\Models\Amenity;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker; // Added this line

class AmenitySeeder extends Seeder
{
    public function run()
    {
        Amenity::factory()->count(50)->create();  // Create 50 amenities for testing
    }
}
