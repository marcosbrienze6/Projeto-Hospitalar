<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\PatientDiagnosis>
 */
class PatientDiagnosisFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'diagnosis' => fake()->name(),
            'situation' => fake()->name(),
            'remedy' => fake()->lastName(),
            'duration_treatment' => fake()->randomNumber(1, 9),
            'return_date' => fake()->date(),
        ];
    }
}
