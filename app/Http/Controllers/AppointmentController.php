<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateAppointmentRequest;
use App\Http\Services\AppointmentService;
use App\Models\Doctor;
use App\Models\Patient;
use Illuminate\Http\Request;

class AppointmentController extends Controller
{
    protected $appointmentService;

    public function __construct(AppointmentService $appointmentService) {
        $this->appointmentService = $appointmentService;
    }

    public function create(CreateAppointmentRequest $request)
    {
        $data = $request->validated();
        $appointment = $this->appointmentService->create($data);

        $doctor = Doctor::with('specialty')->with('agreement')->findOrFail($data['doctor_id']);
        $patient = Patient::findOrFail($data['patient_id']);

        return response()->json([
        'error' => false,
        'message' => 'Consulta marcada com sucesso.',
        'appointment' => $appointment,
        'Paciente' => $patient,
        'Médico responsável' => $doctor
        ]);
    }
}
