<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateDoctorRequest;
use App\Http\Requests\UpdateDoctorRequest;
use App\Http\Services\DoctorService;

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

    public function update(UpdateDoctorRequest $request, $doctorId)
    {
        $data = $request->validated();
        $doctor = $this->serviceInstance->update($data, $doctorId);

        return response()->json(['error' => false, 'message' => "Médico atualizado com sucesso.", 'user' => $doctor]);
    }

    public function delete($doctorId)
    {
       $doctor = $this->serviceInstance->delete($doctorId);
        return response()->json(['error' => false, 'message' => "Médico deletado com sucesso.", 'Usuário deletado' => $doctor]);
    }
}
