<?php

namespace App\Http\Services;

use App\Models\Appointment;
use App\Models\Doctor;
use Illuminate\Support\Str;

class AppointmentService
{
    // public function getFilteredAppointment($data)
    // {
    //     $name = $data['name'] ?? null;
    //     return Patient::with('plan')->when($name ?? null, fn($q) => $q->where('name', 'LIKE', "%$name%"))->get();
    // }

    public function create($data)
    {
        $data['code'] = Str::upper(Str::random(12));
        return Appointment::create($data);
    }

    public function update($appointmentId, $data)
    {
        $appointment = Appointment::find($appointmentId);
        $appointment->update($data);
        return $appointment;
    }

    public function delete($id)
    {
        $appointment = Appointment::find($id);
        $appointment->delete();
    }
}