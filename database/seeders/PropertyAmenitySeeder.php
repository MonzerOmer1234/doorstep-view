<?php

namespace Database\Seeders;

use App\Models\Property;
use App\Models\Amenity;
use Illuminate\Database\Seeder;

class PropertyAmenitySeeder extends Seeder
{
    public function run()
    {
        $property = Property::find(1); // Property with ID 1
        $amenity1 = Amenity::find(1);   // Amenity with ID 1
        $amenity2 = Amenity::find(2);   // Amenity with ID 2

        // Attach amenities to the property if they exist
        if ($property && $amenity1 && $amenity2) {
            $property->amenities()->attach([$amenity1->id, $amenity2->id]);
        }


        // You can also add other properties and amenities as needed
    }
}
