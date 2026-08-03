<?php

namespace Database\Seeders;

use App\Models\User;
<<<<<<< HEAD
=======
use Database\Seeders\ApplicationSeeder;
use Database\Seeders\CandidateSeeder;
use Database\Seeders\PositionSeeder;
>>>>>>> f699f1ba736d0b6a808622a59cbea248d4b5b091
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

<<<<<<< HEAD
         $this->call(RoleSeeder::class);
=======
         $this->call([
            RoleSeeder::class,
            UserSeeder::class,
              CompanySeeder::class,
              CandidateSeeder::class,
        PositionSeeder::class,
        PlanSeeder::class,
        ApplicationSeeder::class,
         ]);
>>>>>>> f699f1ba736d0b6a808622a59cbea248d4b5b091

    }
}
