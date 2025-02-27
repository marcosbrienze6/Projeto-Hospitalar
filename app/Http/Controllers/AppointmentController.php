<?php

namespace App\Http\Controllers;

use App\Http\Services\AppointmentService;
use Illuminate\Http\Request;

class AppointmentController extends Controller
{
    protected $appointmentService;

    public function __construct(AppointmentService $appointmentService) {
        $this->appointmentService = $appointmentService;
    }

    public function create()
    {
        
    }
}
