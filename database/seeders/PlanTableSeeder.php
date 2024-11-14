<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Plan;
class PlanTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        Plan::create([
            'name' => 'Free Plan',
            'monthly_price' => 0,
            'annual_price' => 0,
            'trial_days' => 7,
            'max_site' => 5,
            'max_widget' => 5,
            'max_user' => 2,
            'custom' => 0,
            'analytics' => 0,
            'status' => 1,
        ]);
    }
}
