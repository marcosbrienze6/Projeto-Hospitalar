<?php

namespace App\Http\Controllers;

use App\Http\Requests\{AddPatientRequest, AddPatientToPlanRequest, CreatePatientRequest, GetFilteredPatientRequest, UpdatePatientRequest};
use App\Http\Services\{PatientService, PaymentService};
use App\Models\{Agreement, HealthPlan, Patient, PatientPlan};
use Illuminate\Http\JsonResponse;

class PatientController extends Controller
{
    protected PatientService $patientService;
    protected PaymentService $paymentService;

    public function __construct(PatientService $patientService, PaymentService $paymentService) {
        $this->patientService = $patientService;
        $this->paymentService = $paymentService;
    }

    public function getFilteredPatient(GetFilteredPatientRequest $request): JsonResponse
    {
        $patient = $this->patientService->getFilteredPatient($request->validated());

        return $patient->isEmpty()
            ? response()->json(['error' => true, 'message' => 'Paciente não encontrado.'], 404)
            : response()->json(['error' => false, 'paciente' => $patient]);
    }

    public function create(CreatePatientRequest $request): JsonResponse
    {
        $patient = $this->patientService->create($request->validated());
        return response()->json(['error' => false, 'message' => "Paciente registrado com sucesso.", 'user' => $patient]);
    }

    public function addPatientToPlan(AddPatientRequest $request): JsonResponse
    {   
        $data = $request->validated();
        $plan = HealthPlan::findOrFail($data['plan_id']);
        $patient = Patient::findOrFail($data['patient_id']);

        if ($this->checkExistingRelation(PatientPlan::class, $data['patient_id'], $data['plan_id'])) {
            return response()->json(['error' => true, 'message' => 'Este paciente já está registrado neste plano.'], 400);
        }

        $paymentResult = $this->paymentService->processPayment(
            $plan->price, 'brl', $data['source'], "Pagamento do plano {$plan->name} para paciente {$patient->name}"
        );

        if (!$paymentResult['success']) {
            return response()->json(['error' => true, 'message' => $paymentResult['message']], 400);
        }

        [$isOwner, $responsibleId] = $this->determinePlanOwnership($plan, $data['patient_id']);
        $result = $this->patientService->addPatientToPlan($data['patient_id'], $data['plan_id'], $isOwner, $responsibleId);
        
        return response()->json([
            'error' => false, 'message' => 'Paciente adicionado ao plano com sucesso',
            'Paciente' => $patient, 'Convênio' => $plan, 'Dados da adição' => $result, 'Pagamento' => $paymentResult['data']
        ]);
    }

    public function removePatientFromPlan(UpdatePatientRequest $request): JsonResponse
    {
        return $this->removeRelation(Patient::class, HealthPlan::class, 'plan_id', 'Paciente removido do plano com sucesso');
    }

    public function addPatientToAgreement(AddPatientRequest $request): JsonResponse
    {
        return $this->addRelation(Patient::class, Agreement::class, 'agreement_id', 'Paciente adicionado ao convênio com sucesso');
    }

    public function removePatientFromAgreement(UpdatePatientRequest $request): JsonResponse
    {
        return $this->removeRelation(Patient::class, Agreement::class, 'agreement_id', 'Paciente removido do convênio com sucesso');
    }

    public function update(UpdatePatientRequest $request, int $patientId): JsonResponse
    {
        $patient = $this->patientService->update($patientId, $request->validated());
        return response()->json(['error' => false, 'message' => "Paciente atualizado com sucesso.", 'user' => $patient]);
    }

    public function delete(int $patientId): JsonResponse
    {
        $patient = $this->patientService->delete($patientId);
        return response()->json(['error' => false, 'message' => "Paciente deletado com sucesso.", 'Usuário deletado' => $patient]);
    }

    private function checkExistingRelation(string $model, int $patientId, int $relatedId): bool
    {
        return $model::where('patient_id', $patientId)->where('plan_id', $relatedId)->exists();
    }

    private function determinePlanOwnership(HealthPlan $plan, int $patientId): array
    {
        return in_array($plan->id, [2, 3]) && $plan->patients()->count() === 0
            ? [true, $patientId]
            : [false, null];
    }

    private function addRelation(string $patientModel, string $relatedModel, string $relationId, string $successMessage): JsonResponse
    {
        $data = request()->validated();
        $patient = $patientModel::findOrFail($data['patient_id']);
        $relation = $relatedModel::findOrFail($data[$relationId]);
        $result = $this->patientService->{__FUNCTION__}($data['patient_id'], $data[$relationId]);
        
        return response()->json(['error' => false, 'message' => $successMessage, 'Paciente' => $patient, 'Convênio' => $relation, 'Dados da adição' => $result]);
    }

    private function removeRelation(string $patientModel, string $relatedModel, string $relationId, string $successMessage): JsonResponse
    {
        $data = request()->validated();
        $patient = $patientModel::findOrFail($data['patient_id']);
        $relation = $relatedModel::findOrFail($data[$relationId]);
        $result = $this->patientService->{__FUNCTION__}($data['patient_id'], $data[$relationId]);
        
        return $result === 0
            ? response()->json(['error' => true, 'message' => 'Nenhuma relação encontrada para deletar.'], 404)
            : response()->json(['error' => false, 'message' => $successMessage, 'Paciente' => $patient, 'Convênio' => $relation, 'Registros deletados' => $result]);
    }
}
