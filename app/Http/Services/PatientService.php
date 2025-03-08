<?php

namespace App\Http\Services;

use App\Models\MedicalAgreement;
use App\Models\Patient;
use App\Models\PatientPlan;

class PatientService
{
    public function getFilteredPatient($data)
    {
        $name = $data['name'] ?? null;
        return Patient::with('plan')->when($name ?? null, fn($q) => $q->where('name', 'LIKE', "%$name%"))->get();
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
    
    public function delete($id) 
    {
        $user = Patient::find($id);

        if (!$user) {
            throw new \Exception('Paciente não encontrado.');
        }

        return $user->delete();
    }

    public function addPatientToPlan($patientId, $planId)
    {
        $patientExists = PatientPlan::where('patient_id', $patientId)
        ->where('plan_id', $planId)
        ->exists();

        if($patientExists){
            throw new \Exception('Não é possivel adicionar o mesmo plano duas vezes.');
        }

        return PatientPlan::create([
            'patient_id' => $patientId,
            'is_owner' => true,
            'plan_id' => $planId
        ]);
    }

    public function removePatientFromPlan($patientId, $planId)
    {
        $plan = PatientPlan::where('patient_id', $patientId)
        ->where('plan_id', $planId)
        ->delete();
    }

    public function addPatientToAgreement($patientId, $agreementId)
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

    public function removePatientFromAgreement($patientId, $agreementId)
    {
        $agreement = MedicalAgreement::where('patient_id', $patientId)
        ->where('agreement_id', $agreementId)
        ->delete();
    }
}