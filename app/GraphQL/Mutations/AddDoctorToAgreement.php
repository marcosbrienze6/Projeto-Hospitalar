<?php

namespace App\GraphQL\Mutations;

use App\Models\MedicalAgreement;
use App\Models\Doctor;
use App\Models\Agreement;

class AddDoctorToAgreement
{
    public function __invoke($_, array $args)
    {
        $doctor = Doctor::findOrFail($args['doctor_id']);
        $agreement = Agreement::findOrFail($args['agreement_id']);

        if (MedicalAgreement::where(['agreement_id' => $agreement->id, 'doctor_id' => $doctor->id])->exists()) {
            throw new \Exception('Médico já está vinculado a esse convênio.');
        }

        return MedicalAgreement::create($args);
    }
}