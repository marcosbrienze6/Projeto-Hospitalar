<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateAppointmentRequest;
use App\Http\Requests\UpdateAppointmentRequest;
use App\Http\Services\AppointmentService;
use App\Models\Doctor;
use App\Models\Patient;

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

        $doctor = Doctor::with('specialty', 'agreement')->findOrFail($data['doctor_id']);
        $patient = Patient::findOrFail($data['patient_id']);

        $doctorAgreementIds = $doctor->agreement->pluck('id')->toArray(); 

        if (!in_array($patient->agreement_id, $doctorAgreementIds)) {
            return response()->json([
                'error' => true,
                'message' => 'O paciente só pode marcar consultas com médicos do mesmo convênio.'
            ], 422);
        }

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
        return response()->json(['error' => false,'Consultas encontradas' => $allAppointments]);
    }

    public function getPerId($id)
    {
        $appointment = $this->appointmentService->get($id);

        return response()->json([
            'error' => false,
            'Consulta encontrada' => $appointment
        ]);
    }

    public function update(UpdateAppointmentRequest $request, $appointmentId)
    {
        $data = $request->validated();
        $appointment = $this->appointmentService->update($appointmentId, $data);

        $doctor = Doctor::with('specialty')->with('agreement')->findOrFail($data['doctor_id']);
        $patient = Patient::findOrFail($data['patient_id']);

        return response()->json([
            'error' => false,
            'Consulta atualizada com sucesso.' => $appointment,
            'Paciente' => $patient,
            'Médico responsável' => $doctor,
            ]);
    }

    public function delete($appointmentId)
    {
       $appointment = $this->appointmentService->delete($appointmentId);
       return response()->json(['error' => false, 'message' => "Consulta desmarcada com sucesso."]);
    }
}
