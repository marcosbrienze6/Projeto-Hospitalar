<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Agreement extends Model
{
    use SoftDeletes, HasFactory;

    protected $table = 'agreement';
    
    protected $fillable = ['name'];

    public function doctor()
    {
        return $this->belongsToMany(Doctor::class, 'medical_agreement');
    }
}
