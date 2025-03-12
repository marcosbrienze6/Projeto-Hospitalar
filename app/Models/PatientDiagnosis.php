<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PatientDiagnosis extends Model
{
    use HasFactory;

    protected $table = 'patient_diagnosis';
    
    protected $fillable = [
        'patient_id',
        'diagnosis',
        'remedy',
        'duration_treatment',
        'return_date',
        'symptoms'
    ];

    protected $casts = [
        'symptoms' => 'array',
    ];

    public function patient()
    {
        return $this->belongsTo(Patient::class, 'patient_diagnosis');
    }
}
