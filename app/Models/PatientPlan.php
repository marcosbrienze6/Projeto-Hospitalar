<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PatientPlan extends Model
{
    protected $table = 'patient_plan';
    
    protected $fillable = ['plan_id', 'patient_id'];
}
