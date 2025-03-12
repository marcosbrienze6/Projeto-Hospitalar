<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateAppointmentRequest;
use App\Http\Requests\UpdateAppointmentRequest;
use App\Http\Services\AppointmentService;
use App\Models\Doctor;
use App\Models\Patient;
use App\Models\PatientDiagnosis;

class AppointmentController extends Controller
{
    protected $appointmentService;

    public function __construct(AppointmentService $appointmentService) {
        $this->appointmentService = $appointmentService;
    }

    public function create(CreateAppointmentRequest $request)
    {
        $data = $request->validated();
        
        $doctor = Doctor::with('specialty', 'agreement')->findOrFail($data['doctor_id']);
        $patient = Patient::with('agreement')->findOrFail($data['patient_id']);
        
        $doctorAgreementIds = $doctor->agreement->pluck('id')->toArray();
        $patientAgreementIds = $patient->agreement->pluck('id')->toArray();
        
        if (!array_intersect($patientAgreementIds, $doctorAgreementIds)) {
            return response()->json([
                'error' => true,
                'message' => 'O paciente só pode marcar consultas com médicos do mesmo convênio.'
            ], 422);
        }
        
        $diagnosis = $this->findDiagnosis($data['symptoms'] ?? []);

        $data['diagnosis_id'] = $diagnosis ? $diagnosis->id : null;
        $appointment = $this->appointmentService->create($data);
        
        return response()->json([
        'error' => false,
        'message' => 'Consulta marcada com sucesso.',
        'Consulta' => $appointment,
        'Paciente' => $patient,
        'Médico responsável' => $doctor,
        'Diagnóstico' => $diagnosis
        ]);
    }

    private function findDiagnosis(array $symptoms)
    {
        if (empty($symptoms)) {
            return null;
        }

        return PatientDiagnosis::whereJsonContains('symptoms', $symptoms)->first();
    }

    // public function getFilteredPatient(GetFilteredPatientRequest $request)
    // {
    //     $data = $request->validated();
    //     $patient = $this->patientService->getFilteredPatient($data);

    //     if($patient->isEmpty()) {
    //         return response()->json([
    //         'error' => true,
    //         'message' => 'Paciente não encontrado.'
    //         ], 404);
    //     }

    //     return response()->json([
    //     'error' => false,
    //     'paciente' => $patient,
    //     ]);
    // }

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
