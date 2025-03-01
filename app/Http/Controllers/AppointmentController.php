<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateAppointmentRequest;
use App\Http\Requests\UpdateAppointmentRequest;
use App\Http\Services\AppointmentService;
use App\Models\Appointment;
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

    public function getAll()
    {
        $allAppointments = $this->appointmentService->getAll();
        return response()->json(['error' => false,'users' => $allAppointments]);
    }

    public function update(UpdateAppointmentRequest $request, $appointmentId)
    {
        $data = $request->validated();
        $appointment = $this->appointmentService->update($appointmentId, $data);

        return response()->json(['error' => false, 'Consulta atualizada com sucesso.' => $appointment]);
    }

    public function delete($appointmentId)
    {
       $appointment = $this->appointmentService->delete($appointmentId);
       return response()->json(['error' => false, 'message' => "Consulta desmarcada com sucesso."]);
    }
}
