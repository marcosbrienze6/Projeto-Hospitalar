<?php

namespace Database\Seeders;

use App\Models\Agreement;
use App\Models\Doctor;
use App\Models\HealthPlan;
use App\Models\Patient;
use App\Models\PatientDiagnosis;
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
        // Doctor::factory(10)->create();
        // Patient::factory(10)->create();
        // Agreement::factory(10)->create();
        // Specialty::factory(10)->create();
        // HealthPlan::factory(3)->create();
        PatientDiagnosis::factory(20)->create();
    }
}
