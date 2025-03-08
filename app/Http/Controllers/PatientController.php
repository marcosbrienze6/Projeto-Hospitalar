<?php

namespace App\Http\Controllers;

use App\Http\Requests\AddPatientRequest;
use App\Http\Requests\AddPatientToPlanRequest;
use App\Http\Requests\CreatePatientRequest;
use App\Http\Requests\GetFilteredPatientRequest;
use App\Http\Requests\UpdatePatientRequest;
use App\Http\Services\PatientService;
use App\Models\Agreement;
use App\Models\HealthPlan;
use App\Models\Patient;
use App\Models\PatientPlan;

class PatientController extends Controller
{
    protected $patientService;

    public function __construct(PatientService $patientService) {
        $this->patientService = $patientService;
    }

   public function getFilteredPatient(GetFilteredPatientRequest $request)
   {
        $data = $request->validated();
        $patient = $this->patientService->getFilteredPatient($data);

        if($patient->isEmpty()) {
            return response()->json([
            'error' => true,
            'message' => 'Paciente não encontrado.'
            ], 404);
        }

        return response()->json([
        'error' => false,
        'paciente' => $patient,
        ]);
   }

    public function create(CreatePatientRequest $request)
    {
        $data = $request->validated();
        $patient = $this->patientService->create($data);

        return response()->json(['error' => false, 'message' => "Paciente registrado com sucesso.", 'user' => $patient]);
    }

    public function addPatientToPlan(AddPatientRequest $request)
    {   
        $data = $request->validated();

        $plan = HealthPlan::findOrFail($data['plan_id']);
        $patient = Patient::findOrFail($data['patient_id']);
        
        $patientExists = PatientPlan::where('patient_id', $data['patient_id'])
        ->where('plan_id', $data['plan_id'])
        ->exists();

        if ($patientExists) {
            return response()->json([
                'error' => true,
                'message' => 'Este paciente já está registrado neste plano.',
            ], 400);
        }

        $isOwner = false;
        $responsibleId = null;

        if ($plan->id == 3) { 
            $currentPatientsCount = $plan->patients()->count();
            if ($currentPatientsCount == 0) {
                $isOwner = true; 
            }    
            $responsibleId = $data['patient_id'];       
        }

        $result = $this->patientService->addPatientToPlan($data['patient_id'], $data['plan_id'], $isOwner, $responsibleId);
        
        return response()->json([
        'error' => false,
        'message' => 'Paciente adicionado ao plano com sucesso',
        'Paciente' => $patient,
        'Convênio' => $plan,
        'Dados da adição' => $result
        ]);
    }

    public function removePatientFromPlan(UpdatePatientRequest $request)
    {
        $data = $request->validated();

        $patient = Patient::findOrFail($data['patient_id']);
        $plan = HealthPlan::findOrFail($data['plan_id']);

        $res = $this->patientService->removePatientFromPlan($data['patient_id'], $data['plan_id']);

        if ($res === 0) {
            return response()->json([
            'error' => true,
            'message' => 'Nenhuma relação encontrada para deletar.'
            ], 404);
        }

        return response()->json([
        'error' => false,
        'message' => 'Paciente removido do plano com sucesso',
        'Paciente' => $patient,
        'Convênio' => $plan,
        'Registros deletados' => $res
        ]); 
    }

    public function addPatientToAgreement(AddPatientRequest $request)
    {
        $data = $request->validated();

        $patient = Patient::findOrFail($data['patient_id']);
        $agreement = Agreement::findOrFail($data['agreement_id']);
        
        $result = $this->patientService->addPatientToAgreement($data['patient_id'], $data['agreement_id']);
        
        return response()->json([
        'error' => false,
        'message' => 'Paciente adicionado ao convênio com sucesso',
        'Paciente' => $patient,
        'Convênio' => $agreement,
        'Dados da adição' => $result
        ]);
    }

    public function removePatientFromAgreement(UpdatePatientRequest $request)
    {
        $data = $request->validated();

        $patient = Patient::findOrFail($data['patient_id']);
        $agreement = Agreement::findOrFail($data['agreement_id']);

        $res = $this->patientService->removePatientFromAgreement($data['patient_id'], $data['agreement_id']);

        if ($res === 0) {
            return response()->json([
            'error' => true,
            'message' => 'Nenhuma relação encontrada para deletar.'
            ], 404);
        }

        return response()->json([
        'error' => false,
        'message' => 'Paciente removido do convênio com sucesso',
        'Paciente' => $patient,
        'Convênio' => $agreement,
        'Registros deletados' => $res
        ]);
    }

    public function update(UpdatePatientRequest $request, $patientId)
    {
        $data = $request->validated();
        $patient = $this->patientService->update($patientId, $data);

        return response()->json(['error' => false, 'message' => "Paciente atualizado com sucesso.", 'user' => $patient]);
    }

    public function delete($patientId)
    {
       $patient = $this->patientService->delete($patientId);
       return response()->json(['error' => false, 'message' => "Paciente deletado com sucesso.", 'Usuário deletado' => $patient]);
    }
}
