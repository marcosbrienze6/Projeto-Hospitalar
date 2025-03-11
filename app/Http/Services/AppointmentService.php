<?php

namespace App\Http\Services;

use App\Models\Appointment;
use App\Models\Doctor;
use Illuminate\Support\Str;

class AppointmentService
{
    public function getAll()
    {
        return Appointment::all();
    }

    public function get($id)
    {
        return Appointment::find($id);
    }

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