<?php

namespace Database\Seeders;
use App\Models\User;
use Illuminate\Database\Seeder;


class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => bcrypt('password'),
            'phone_number' => '123-456-7890',
            'user_type' => 'regular',
        ]);

            $this->call([
            PropertySeeder::class,
            AmenitySeeder::class,
            PropertyAmenitySeeder::class,
            VisitRequestsTableSeeder::class
        ]);
    }
}
