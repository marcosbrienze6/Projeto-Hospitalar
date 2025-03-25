<?php

namespace App\Http\Controllers;

use App\Http\Requests\{AddDoctorRequest, CreateDoctorRequest, UpdateDoctorRequest};
use App\Http\Services\DoctorService;
use App\Models\{Agreement, Doctor, MedicalAgreement, MedicalSpecialty, Specialty};
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\JsonResponse;

class DoctorController extends Controller
{
    protected DoctorService $doctorService;

    public function __construct(DoctorService $doctorService)
    {
        $this->doctorService = $doctorService;
    }

    public function indexAll(): JsonResponse
    {
        return response()->json(['error' => false, 'users' => $this->doctorService->getAll()]);
    }

    public function index(int $doctorId): JsonResponse
    {
        return response()->json(['error' => false, 'message' => 'Médico encontrado.', 'user' => $this->doctorService->get($doctorId)]);
    }

    public function create(CreateDoctorRequest $request): JsonResponse
    {
        return response()->json(['error' => false, 'message' => 'Médico registrado com sucesso.', 'user' => $this->doctorService->create($request->validated())]);
    }

    private function checkExistingRelation($model, array $conditions): bool
    {
        return $model::where($conditions)->exists();
    }

    public function addDoctorToAgreement(AddDoctorRequest $request): JsonResponse
    {
        $data = $request->validated();
        $doctor = Doctor::findOrFail($data['doctor_id']);
        $agreement = Agreement::findOrFail($data['agreement_id']);

        if ($this->checkExistingRelation(MedicalAgreement::class, ['agreement_id' => $agreement->id, 'doctor_id' => $doctor->id])) {
            return response()->json(['error' => true, 'message' => 'Não é possível adicionar o mesmo convênio duas vezes.']);
        }

        return response()->json([
            'message' => 'Médico adicionado ao convênio com sucesso',
            'Médico' => $doctor,
            'Convênio' => $agreement,
            'Dados da adição' => MedicalAgreement::create($data),
        ]);
    }

    public function addDoctorToSpecialty(AddDoctorRequest $request): JsonResponse
    {
        $data = $request->validated();
        $doctor = Doctor::findOrFail($data['doctor_id']);
        $specialty = Specialty::findOrFail($data['specialty_id']);

        if ($this->checkExistingRelation(MedicalSpecialty::class, ['specialty_id' => $specialty->id, 'doctor_id' => $doctor->id])) {
            return response()->json(['error' => true, 'message' => 'Não é possível adicionar a mesma especialidade duas vezes.']);
        }

        return response()->json([
            'message' => 'Médico adicionado à especialidade com sucesso',
            'Especialidade' => $specialty,
            'Médico' => $doctor,
            'Dados da adição' => MedicalSpecialty::create($data),
        ]);
    }

    public function update(UpdateDoctorRequest $request, int $doctorId): JsonResponse
    {
        return response()->json(['error' => false, 'message' => 'Médico atualizado com sucesso.', 'user' => $this->doctorService->update($doctorId, $request->validated())]);
    }

    public function delete(int $doctorId): JsonResponse
    {
        if (!Auth::check()) {
            return response()->json(['error' => true, 'message' => 'Usuário não autenticado.'], 401);
        }
        
        return response()->json(['error' => false, 'message' => 'Médico deletado com sucesso.', 'Usuário deletado' => $this->doctorService->delete($doctorId)]);
    }
}

