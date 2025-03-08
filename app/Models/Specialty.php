<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Specialty extends Model
{
    use HasFactory;

    protected $table = 'specialty';

    protected $fillable = ['name'];

    public function doctor()
    {
        return $this->belongsToMany(Doctor::class, 'medical_specialty');
    }
}
