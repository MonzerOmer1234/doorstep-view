<?php

namespace Database\Factories;

use App\Models\Amenity;
use Illuminate\Database\Eloquent\Factories\Factory;

class AmenityFactory extends Factory
{
    protected $model = Amenity::class;

    public function definition()
    {
        return [
            'name' => $this->faker->company,
            
            'address' => $this->faker->address,
            'category' => $this->faker->randomElement(['school', 'park', 'hospital', 'shopping mall', 'restaurant']),
           'latitude' => $this->faker->latitude,
            'longitude' => $this->faker->longitude,
        ];
    }
}
