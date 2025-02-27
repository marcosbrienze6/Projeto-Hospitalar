<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateAppointmentRequest extends FormRequest
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
            'name' => 'required|string|max:255',
            'patient_id' => 'required|exists:patient,id',
            'doctor_id' => 'required|exists:doctor,id',
            'appointment_date' => 'required|date',
            'appointment_time' => 'required|time',
            'status' => 'required|in:active,inactive'
        ];
    }
}
