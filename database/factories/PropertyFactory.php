<?php

namespace Database\Factories;

use App\Models\Property;
use Illuminate\Database\Eloquent\Factories\Factory;

class PropertyFactory extends Factory
{
    protected $model = Property::class;

    public function definition()
    {
        return [
            'user_id' => 1,
            'description' => $this->faker->text(30),

            'title' => $this->faker->title,
            'price' => $this->faker->numberBetween(100000, 1000000),
            'location' => $this->faker->city,
            'latitude' => $this->faker->latitude,
            'longitude' => $this->faker->longitude,
            'bedrooms' => $this->faker->numberBetween(1, 5),
            'bathrooms' => $this->faker->numberBetween(1, 5),
            'area' => $this->faker->numberBetween(50, 500),
            'property_type' => $this->faker->randomElement(['apartment', 'villa', 'land']),

        ];
    }
}
