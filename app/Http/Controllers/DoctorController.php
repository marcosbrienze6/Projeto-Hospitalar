<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateDoctorRequest;
use App\Http\Requests\UpdateDoctorRequest;
use App\Http\Services\DoctorService;
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

    public function addDoctor(AddDoctorRequest $request)
    {

    }

    public function update(UpdateDoctorRequest $request, $doctorId)
    {
        $user = Auth::user();
        if (!$user) {
            return response()->json(['error' => true, 'message' => 'Usuário não autenticado.'], 401);
        }

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
