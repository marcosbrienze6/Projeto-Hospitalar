<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Agreement extends Model
{
    use HasFactory;

    protected $table = 'agreement';
    
    protected $fillable = ['name'];

    public function doctor()
    {
        return $this->belongsToMany(Doctor::class, 'medical_agreement');
    }
}
