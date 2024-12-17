<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;
use Carbon\Carbon;

class VisitRequestsTableSeeder extends Seeder
{
    public function run()
    {
        $faker = Faker::create();

        // Generate 10 sample visit requests
        for ($i = 0; $i < 10; $i++) {
            DB::table('visit_requests')->insert([
                'property_id' => $faker->numberBetween(1, 50), // Random property_id between 1 and 50
                'user_id' => 1,     // Random user_id between 1 and 20
                'requested_at' => Carbon::now()->subDays(rand(0, 30)), // Random requested_at within the last 30 days
                'visitor_name' => $faker->name,
                'visitor_email' => $faker->unique()->safeEmail,
                'visit_date' => Carbon::now()->addDays(rand(1, 14)), // Random visit_date between 1 and 14 days from now
                'status' => $faker->randomElement(['pending', 'approved', 'rejected']), // Random status
                'created_at' => now(),
                'updated_at' => now(),
                'access_code' => $faker->word()
            ]);
        }
    }
}
