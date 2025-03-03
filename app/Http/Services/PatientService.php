<?php

namespace App\Http\Services;

use App\Models\Patient;

class PatientService
{
    public function getAll()
    {
        return Patient::all();
    }

    public function get($id)
    {
        return Patient::find($id);
    }

    public function create($data)
    {
        return Patient::create($data);
    }

    public function update($patientId, $data)
    {
        $user = Patient::find($patientId);
        $user->update($data);
        return $user;
    }

    public function delete($id)
    {
        $user = Patient::find($id);
        $user->delete();
    }
}