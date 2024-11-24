<?php

namespace Database\Seeders;

use App\Models\Property;
use Illuminate\Database\Seeder;

class PropertySeeder extends Seeder
{
    public function run()
    {
        // Create 50 random properties
        Property::factory()->count(50)->create();
    }
}
