<?php

namespace Database\Seeders;

use App\Models\Plan;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class PlanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        $basic = Plan::create([
            'name' => 'Basic',
            'slug' => Str::slug('Basic'),
            'price' => 2000,
            'currency' => 'EGP',
            'duration_days' => 30,
            'description' => 'Basic Plan',
            'is_custom' => false,
            'is_active' => true,
            'stripe_price_id' => 'price_1TzpBz22Zz086J0hkXQyOS4B',

        ]);
        $basic->features()->createMany([
            ['feature' => 'Up to 5 job announcements'],
            ['feature' => '500 interview credits/month'],
            ['feature' => 'Basic AI evaluation'],
            ['feature' => 'Standard reports'],
            ['feature' => 'Email support'],
        ]);

        $perimum = Plan::create([
            'name' => 'Premium',
            'slug' => Str::slug('Premium'),
            'price' => 10000,
            'currency' => 'EGP',
            'duration_days' => 30,
            'description' => 'Premium Plan',
            'is_custom' => false,
            'is_active' => true,
            'stripe_price_id' => 'price_1TzpCY22Zz086J0hd7vdtSJs'
        ]);
        $perimum->features()->createMany([
            ['feature' => 'Unlimited job announcements'],
            ['feature' => '3000 interview credits/month'],
            ['feature' => 'Advanced analytics'],
            ['feature' => 'Team collaboration'],
            ['feature' => 'Priority support'],
            ['feature' => 'API access'],
        ]);

        $enterprise =  Plan::create([
            'name' => 'Enterprise',
            'slug' => Str::slug('Enterprise'),
            'price' => null,
            'currency' => 'EGP',
            'duration_days' => 30,
            'description' => 'Enterprise Plan',
            'is_custom' => true,
            'is_active' => true,
            'stripe_price_id' => null,
        ]);
        $enterprise->features()->createMany([
            ['feature' => 'Everything in Premium'],
            ['feature' => 'Dedicated account manager'],
            ['feature' => 'Custom integrations'],
            ['feature' => 'SLA & on-site training'],
        ]);
    }
}
