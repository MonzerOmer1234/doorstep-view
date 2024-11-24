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
<<<<<<< HEAD
=======
        $this->call(PropertiesTableSeeder::class);
        $this->call(AmenitySeeder::class);
        // $this->call(ApartmentSeeder::class);
>>>>>>> a6659e284f56c77e1a7ee2ac560a00302d5c39d4

        $this->call([
            PropertySeeder::class,
            AmenitySeeder::class,
        ]);
    }
}
