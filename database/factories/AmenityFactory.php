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
            'icon' => $this->faker->text(10),
            'category' => $this->faker->randomElement(['school', 'park', 'hospital', 'shopping mall', 'bus stop', 'restaurant']),
            'latitude' => $this->faker->latitude,
            'longitude' => $this->faker->longitude,
        ];
    }
}
