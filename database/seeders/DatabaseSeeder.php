<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Artisan;
use App\Models\Utility;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        Artisan::call('module:migrate LandingPage');  
        Artisan::call('module:seed LandingPage');  

        if(\Request::route()->getName()!='LaravelUpdater::database')
        {
            $this->call(UsersTableSeeder::class);
            $this->call(PlanTableSeeder::class);
        }
        else
        {
            Utility::languagecreate();
        }
    }
}
