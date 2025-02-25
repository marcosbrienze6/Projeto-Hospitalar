<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MedicalAgreement extends Model
{
    protected $table = 'medical_agreement';
    
    protected $fillable = ['agreement_id', 'doctor_id'];
}
