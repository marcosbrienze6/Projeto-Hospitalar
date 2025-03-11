<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MedicalSpecialty extends Model
{
    protected $table = 'medical_specialty';
    
    protected $fillable = ['doctor_id', 'specialty_id'];
}
