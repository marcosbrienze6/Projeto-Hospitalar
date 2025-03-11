<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HealthPlan extends Model
{
    use HasFactory;

    protected $table = 'health_plan';
    protected $fillable = ['name', 'price', 'max_people'];

    public function patients()
    {
        return $this->belongsToMany(Patient::class, 'patient_health_plan', 'plan_id', 'patient_id')
        ->withTimestamps();
    }

    public function owners()
    {
        return $this->patients()->wherePivot('is_owner', true);
    }
}
