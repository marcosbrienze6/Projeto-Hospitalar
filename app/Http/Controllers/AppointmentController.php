<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateAppointmentRequest;
use App\Http\Requests\UpdateAppointmentRequest;
use App\Http\Services\AppointmentService;
use App\Models\Doctor;
use App\Models\Patient;
use App\Models\PatientDiagnosis;
use Illuminate\Http\JsonResponse;

class AppointmentController extends Controller
{
    protected AppointmentService $appointmentService;

    public function __construct(AppointmentService $appointmentService) 
    {
        $this->appointmentService = $appointmentService;
    }

    public function create(CreateAppointmentRequest $request): JsonResponse
    {
        $data = $request->validated();

        $doctor = Doctor::with(['specialty', 'agreement'])->findOrFail($data['doctor_id']);
        $patient = Patient::with('agreement')->findOrFail($data['patient_id']);

        if (!$this->hasMatchingAgreements($doctor, $patient)) {
            return response()->json(['error' => true, 'message' => 'O paciente só pode marcar consultas com médicos do mesmo convênio.'], 422);
        }

        $data['diagnosis_id'] = $this->findDiagnosis($data['symptoms'] ?? [])?->id;
        $appointment = $this->appointmentService->create($data);

        return response()->json([
            'error' => false,
            'message' => 'Consulta marcada com sucesso.',
            'Consulta' => $appointment,
            'Paciente' => $patient,
            'Médico responsável' => $doctor,
            'Diagnóstico' => $data['diagnosis_id'] ? $this->findDiagnosis($data['symptoms'] ?? []) : null
        ]);
    }

    public function update(UpdateAppointmentRequest $request, $appointmentId): JsonResponse
    {
        $data = $request->validated();
        $appointment = $this->appointmentService->update($appointmentId, $data);

        return response()->json([
            'error' => false,
            'Consulta atualizada com sucesso.' => $appointment,
            'Paciente' => Patient::findOrFail($data['patient_id']),
            'Médico responsável' => Doctor::with(['specialty', 'agreement'])->findOrFail($data['doctor_id'])
        ]);
    }

    public function delete($appointmentId): JsonResponse
    {
        $this->appointmentService->delete($appointmentId);
        return response()->json(['error' => false, 'message' => 'Consulta desmarcada com sucesso.']);
    }

    private function hasMatchingAgreements(Doctor $doctor, Patient $patient): bool
    {
        return !empty(array_intersect(
            $doctor->agreement->pluck('id')->toArray(),
            $patient->agreement->pluck('id')->toArray()
        ));
    }

    private function findDiagnosis(array $symptoms): ?PatientDiagnosis
    {
        return empty($symptoms) ? null : PatientDiagnosis::whereJsonContains('symptoms', $symptoms)->first();
    }
}
