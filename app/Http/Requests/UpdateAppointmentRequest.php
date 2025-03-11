<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateAppointmentRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'patient_id' => 'nullable|exists:patient,id',
            'doctor_id' => 'nullable|exists:doctor,id',
            'appointment_date' => 'nullable|date|after_or_equal:today',
            'appointment_time' => 'nullable|date_format:H:i',
            'status' => 'nullable|in:active,cancelled,completed'
        ];
    }
}
