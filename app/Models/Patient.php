<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Patient extends Model
{
    use HasFactory,SoftDeletes;
    
    protected $table = 'patient';

    protected $fillable = ['name', 'address', 'cpf', 'phone_number'];

    public function agreement()
    {
        return $this->belongsToMany(Agreement::class, 'medical_agreement');
    }

    public function plan()
    {
        return $this->belongsToMany(HealthPlan::class, 'patient_plan', 'patient_id', 'plan_id');
    }
}
