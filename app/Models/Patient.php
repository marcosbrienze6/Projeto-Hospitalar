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
}
