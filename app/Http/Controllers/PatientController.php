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

    public function index($doctorId)
    {
        $doctor = $this->patientService->get($doctorId);
        return response()->json(['error' => false, 'message' => "Médico encontrado.", 'user' => $doctor]);
    }

    public function create(CreatePatientRequest $request)
    {
        $data = $request->validated();
        $doctor = $this->patientService->create($data);

        return response()->json(['error' => false, 'message' => "Médico registrado com sucesso.", 'user' => $doctor]);
    }

    public function addPatientToAgreement(AddPatientRequest $request)
    {
        $data = $request->validated();

        $doctor = Patient::findOrFail($data['doctor_id']);
        $agreement = Agreement::findOrFail($data['agreement_id']);

        $existingDoctor = MedicalAgreement::where('agreement_id', $agreement->id)
        ->where('doctor_id', $doctor->id)
        ->exists();
                                                                   
        if ($existingDoctor) {
            return response()->json([
            'error' => true,
            'message' => 'Não é possível adicionar o mesmo convênio duas vezes.'
            ]);
        } 

        $agreement = MedicalAgreement::create($data);

        return response()->json([
            'message' => 'Médico adicionado ao convênio com sucesso',
            'agreement' => $agreement,
            'doctor' => $doctor
        ]);
    }

    public function addDoctorToSpecialty(AddPatientRequest $request)
    {
        $data = $request->validated();
        
        $doctor = Patient::findOrFail($data['doctor_id']);
        $specialty = Specialty::findOrFail($data['specialty_id']);
        
        $existingDoctor = MedicalSpecialty::where('specialty_id', $specialty->id)
        ->where('doctor_id', $doctor->id)
        ->exists();
        
        if ($existingDoctor) {
            return response()->json([
            'error' => true,
            'message' => 'Não é possível adicionar a mesma especialidade duas vezes.'
            ]);
        } 

        $specialty = MedicalSpecialty::create($data);

        return response()->json([
            'message' => 'Médico adicionado ao convênio com sucesso',
            'agreement' => $specialty,
            'doctor' => $doctor
        ]);
    }

    public function update(UpdatePatientRequest $request, $doctorId)
    {
        $data = $request->validated();
        $doctor = $this->patientService->update($doctorId, $data);

        return response()->json(['error' => false, 'message' => "Médico atualizado com sucesso.", 'user' => $doctor]);
    }

    public function delete($doctorId)
    {
        $user = Auth::user();
        if (!$user) {
            return response()->json(['error' => true, 'message' => 'Usuário não autenticado.'], 401);
        }

       $doctor = $this->patientService->delete($doctorId);
       return response()->json(['error' => false, 'message' => "Médico deletado com sucesso.", 'Usuário deletado' => $doctor]);
    }
}
