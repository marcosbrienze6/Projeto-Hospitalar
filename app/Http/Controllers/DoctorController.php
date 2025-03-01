<?php

namespace App\Http\Controllers;

use App\Http\Requests\AddDoctorRequest;
use App\Http\Requests\CreateDoctorRequest;
use App\Http\Requests\UpdateDoctorRequest;
use App\Http\Services\DoctorService;
use App\Models\Agreement;
use App\Models\Doctor;
use App\Models\MedicalAgreement;
use App\Models\MedicalSpecialty;
use App\Models\Specialty;
use Illuminate\Support\Facades\Auth;


class DoctorController extends Controller
{
    protected $serviceInstance;

    public function __construct(DoctorService $serviceInstance) {
        $this->serviceInstance = $serviceInstance;
    }

    public function indexAll()
    {
        $allUsers = $this->serviceInstance->getAll();
        return response()->json(['error' => false,'users' => $allUsers]);

    }

    public function index($doctorId)
    {
        $doctor = $this->serviceInstance->get($doctorId);
        return response()->json(['error' => false, 'message' => "Médico encontrado.", 'user' => $doctor]);
    }

    public function create(CreateDoctorRequest $request)
    {
        $data = $request->validated();
        $doctor = $this->serviceInstance->create($data);

        return response()->json(['error' => false, 'message' => "Médico registrado com sucesso.", 'user' => $doctor]);
    }

    public function addDoctorToAgreement(AddDoctorRequest $request)
    {
        $data = $request->validated();

        $doctor = Doctor::findOrFail($data['doctor_id']);
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

    public function addDoctorToSpecialty(AddDoctorRequest $request)
    {
        $data = $request->validated();
        
        $doctor = Doctor::findOrFail($data['doctor_id']);
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

    public function update(UpdateDoctorRequest $request, $doctorId)
    {
        $data = $request->validated();
        $doctor = $this->serviceInstance->update($doctorId, $data);

        return response()->json(['error' => false, 'message' => "Médico atualizado com sucesso.", 'user' => $doctor]);
    }

    public function delete($doctorId)
    {
        $user = Auth::user();
        if (!$user) {
            return response()->json(['error' => true, 'message' => 'Usuário não autenticado.'], 401);
        }

       $doctor = $this->serviceInstance->delete($doctorId);
       return response()->json(['error' => false, 'message' => "Médico deletado com sucesso.", 'Usuário deletado' => $doctor]);
    }
}
