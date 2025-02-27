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
        $data['CRM'] = Str::upper(Str::random(8));
        return Appointment::create($data);
    }

    public function update($appointmentId, $data)
    {
        $user = Appointment::find($appointmentId);
        $user->update($data);
        return $user;
    }

    public function delete($id)
    {
        $user = Appointment::find($id);
        $user->delete();
    }
}