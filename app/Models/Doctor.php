<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Doctor extends Model
{
    use SoftDeletes, HasFactory;

    protected $table = 'doctor';

    protected $fillable = ['name', 'hire_of_date'];

    public function agreement()
    {
        return $this->belongsToMany(Agreement::class, 'medical_agreement');
    }
}
