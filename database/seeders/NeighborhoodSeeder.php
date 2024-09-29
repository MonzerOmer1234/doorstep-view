<?php

namespace Database\Seeders;

use App\Models\Neighborhood;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Faker\Factory as Faker;
use Illuminate\Database\Seeder;

class NeighborhoodSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        $faker = Faker::create();
        for($i = 0 ; $i < 100 ; $i++){
            Neighborhood::create([
                'name' => 'n1',
                'description' => 'D1',
            ]);
        }

    }
}
