<?php

namespace App\Http\Controllers;

use App\Http\Requests\AddPatientRequest;
use App\Http\Requests\CreatePatientRequest;
use App\Http\Requests\UpdatePatientRequest;
use App\Http\Services\PatientService;
use App\Models\Agreement;
use App\Models\MedicalAgreement;
use App\Models\MedicalSpecialty;
use App\Models\Patient;
use App\Models\Specialty;
use Illuminate\Support\Facades\Auth;

use Illuminate\Http\Request;

class PatientController extends Controller
{
    protected $patientService;

    public function __construct(PatientService $patientService) {
        $this->patientService = $patientService;
    }

    public function indexAll()
    {
        $allPatients = $this->patientService->getAll();
        return response()->json(['error' => false,'users' => $allPatients]);

    }

    public function index($patientId)
    {
        $doctor = $this->patientService->get($patientId);
        return response()->json(['error' => false, 'message' => "Médico encontrado.", 'user' => $doctor]);
    }

    public function create(CreatePatientRequest $request)
    {
        $data = $request->validated();
        $doctor = $this->patientService->create($data);

        return response()->json(['error' => false, 'message' => "Paciente registrado com sucesso.", 'user' => $doctor]);
    }

    public function addPatientToAgreement(AddPatientRequest $request)
    {
        $data = $request->validated();

        $patient = Patient::findOrFail($data['patient_id']);
        $agreement = Agreement::findOrFail($data['agreement_id']);
        
        $existingPatient = MedicalAgreement::where('agreement_id', $agreement->id)
        ->where('patient_id', $patient->id)
        ->exists();

        if ($existingPatient) {
            return response()->json([
                'error' => true,
                'message' => 'Não é possível adicionar o mesmo convênio duas vezes.'
            ]);
        } 
        
        $res = MedicalAgreement::create($data);

        return response()->json([
        'error' => false,
        'message' => 'Paciente adicionado ao convênio com sucesso',
        'Paciente' => $patient,
        'Convênio' => $agreement,
        'Dados da adição' => $res
        ]);
    }

    public function removePatientFromAgreement(UpdatePatientRequest $request)
    {
        $data = $request->validated();

        $patient = Patient::findOrFail($data['patient_id']);
        $agreement = Agreement::findOrFail($data['agreement_id']);

        $res = $this->patientService->removePatient($data['patient_id'], $data['agreement_id']);

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
       $patient = $this->patientService->deletePatient($patientId);
       return response()->json(['error' => false, 'message' => "Paciente deletado com sucesso.", 'Usuário deletado' => $patient]);
    }
}
