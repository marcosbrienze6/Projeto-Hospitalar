<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Appointment extends Model
{
    use HasFactory;

    protected $table = 'appointment';
    
    protected $fillable = ['topic', 'code', 'patient_id', 'doctor_id', 'appointment_date', 'appointment_time', 'status'];

    public function doctor()
    {
        return $this->belongsTo(Doctor::class, 'medical_agreement');
    }

    public function patient()
    {
        return $this->belongsTo(Patient::class, 'medical_agreement');
    }
}
