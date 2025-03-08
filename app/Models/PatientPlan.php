<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PatientPlan extends Model
{
    protected $table = 'patient_health_plan';
    
    protected $fillable = ['plan_id', 'is_owner', 'relationship', 'patient_id'];
}
