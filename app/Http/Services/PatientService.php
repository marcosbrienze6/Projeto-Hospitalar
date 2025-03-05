<?php

namespace App\Http\Services;

use App\Models\MedicalAgreement;
use App\Models\Patient;
use App\Models\PatientPlan;

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

    public function addPlan($patientId, $planId)
    {
        $plan = PatientPlan::where('patient_id', $patientId)
        ->where('plan_id', $planId)
        ->exists();

        if($plan){
            throw new \Exception('Não é possivel adicionar o mesmo plano duas vezes.');
        }

        return PatientPlan::create([
            'patient_id' => $patientId,
            'plan_id' => $planId
        ]);
    }

    public function addAgreement($patientId, $agreementId)
    {
        $agreement = MedicalAgreement::where('patient_id', $patientId)
        ->where('agreement_id', $agreementId)
        ->exists();

        if($agreement){
            throw new \Exception('Não é possivel adicionar o mesmo convênio duas vezes.');
        }

        return MedicalAgreement::create([
            'patient_id' => $patientId,
            'agreement_id' => $agreementId
        ]);
    }

    public function removeAgreement($patientId, $agreementId)
    {
        $agreement = MedicalAgreement::where('patient_id', $patientId)
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