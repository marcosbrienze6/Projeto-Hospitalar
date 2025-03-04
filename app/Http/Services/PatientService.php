<?php

namespace App\Http\Services;

use App\Models\MedicalAgreement;
use App\Models\Patient;

class PatientService
{
    public function getAll()
    {
        return Patient::all();
    }

    public function get($id)
    {
        return Patient::find($id);
    }

    public function create($data)
    {
        return Patient::create($data);
    }

    public function update($patientId, $data)
    {
        $user = Patient::find($patientId);
        $user->update($data);
        return $user;
    }

    public function removePatient($patientId, $agreementId)
    {
        return MedicalAgreement::where('patient_id', $patientId)
        ->where('agreement_id', $agreementId)
        ->delete();
    }

    public function deletePatient($id) 
    {
        $user = Patient::find($id);

        if (!$user) {
            throw new \Exception('Paciente não encontrado.');
        }

        return $user->delete();
    }
}