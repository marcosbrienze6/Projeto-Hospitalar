<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HealthPlan extends Model
{
    use HasFactory;

    protected $table = 'health_plan';
    protected $fillable = ['name', 'price', 'max_people'];

    public function patient()
    {
        return $this->belongsToMany(Patient::class, 'patient_plan', 'plan_id', 'patient_id');
    }
}
