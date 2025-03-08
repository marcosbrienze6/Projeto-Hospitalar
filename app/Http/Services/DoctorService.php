<?php

namespace App\Http\Services;

use App\Models\Doctor;
use Illuminate\Support\Str;

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
        $data['CRM'] = Str::upper(Str::random(8));
        return Doctor::create($data);
    }

    public function update($doctorId, $data)
    {
        $user = Doctor::find($doctorId);
        $user->update($data);
        return $user;
    }

    public function delete($id)
    {
        $user = Doctor::find($id);
        $user->delete();
    }
}