<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PatientPlan extends Model
{
    protected $table = 'patient_health_plan';
    
    protected $fillable = ['plan_id', 'is_owner', 'relationship', 'responsible_id', 'patient_id'];

    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }

    public function plan()
    {
        return $this->belongsTo(HealthPlan::class);
    }

    public function responsible()
    {
        return $this->belongsTo(Patient::class, 'responsible_id');
    }
}
