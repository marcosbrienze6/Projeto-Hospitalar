<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Patient extends Model
{
    use HasFactory;
    
    protected $table = 'patient';

    protected $fillable = ['name',
        'address',
        'cpf',
        'phone_number'
    ];

    public function agreement()
    {
        return $this->belongsToMany(Agreement::class, 'medical_agreement');
    }

    public function healthPlans()
    {
        return $this->belongsToMany(HealthPlan::class, 'patient_health_plan')
        ->withPivot('is_owner', 'relationship', 'responsible_id')
        ->withTimestamps();
    }

    public function plan()
    {
        return $this->belongsToMany(HealthPlan::class, 'patient_plan', 'patient_id', 'plan_id');
    }

    public function dependents()
    {
        return $this->hasMany(PatientPlan::class, 'responsible_id');
    }
}
