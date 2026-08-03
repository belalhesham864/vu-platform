<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $roles = ['HR', 'HR-Interviewer', 'Tech-Interviewer', 'Account-Manager'];

        User::factory(30)->create()->each(function (User $user, int $index) use ($roles) {
            $user->assignRole($roles[$index % count($roles)]);
        });
    }
}
