<?php

namespace App\Http\Services;

use App\Models\Doctor;

class DoctorService
{
    public function getAll()
    {
        return Doctor::all();
    }

    public function get($id)
    {
        return Doctor::find($id);
    }

    public function create($data)
    {
        return Doctor::create($data);
    }

    public function update($id, $data)
    {
        $user = Doctor::find($id);
        $user->update($data);
        return $user;
    }

    public function delete($id)
    {
        $user = Doctor::find($id);
        $user->delete();
    }
}