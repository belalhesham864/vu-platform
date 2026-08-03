<?php

namespace Database\Seeders;

use App\Models\User;
use Database\Seeders\ApplicationSeeder;
use Database\Seeders\CandidateSeeder;
use Database\Seeders\PositionSeeder;
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
        $this->call([
            RoleSeeder::class,
            CompanySeeder::class,
            CategorySeeder::class,
            UserSeeder::class,
            CandidateSeeder::class,
            PositionSeeder::class,
            PlanSeeder::class,
            ApplicationSeeder::class,
            InterviewSeeder::class,
        ]);
    }
}
