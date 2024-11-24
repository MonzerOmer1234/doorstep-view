<?php

namespace Database\Seeders;

use App\Models\Amenity;
use Illuminate\Database\Seeder;

class AmenitySeeder extends Seeder
{
    public function run()
    {
        // Create 100 random amenities
        Amenity::factory()->count(100)->create();
    }
}
