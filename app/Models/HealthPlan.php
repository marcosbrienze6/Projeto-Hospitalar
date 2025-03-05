<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HealthPlan extends Model
{
    use HasFactory;

    protected $table = 'health_plan';
    protected $fillable = ['name', 'price'];

    public function patient()
    {
        return $this->belongsTo(Patient::class, 'patient_plan');
    }
}
