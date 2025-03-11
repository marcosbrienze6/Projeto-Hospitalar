<?php

namespace Database\Seeders;

use App\Models\Agreement;
<<<<<<< HEAD
use App\Models\Doctor;
=======
use App\Models\HealthPlan;
>>>>>>> main
use App\Models\Patient;
use App\Models\Specialty;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();
<<<<<<< HEAD
        Doctor::factory(10)->create();
        Patient::factory(10)->create();
=======

>>>>>>> main
        Agreement::factory(10)->create();
        Specialty::factory(10)->create();
        Patient::factory(15)->create();
        HealthPlan::factory(3)->create();
    }
}
