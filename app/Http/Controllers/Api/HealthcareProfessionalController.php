<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\HealthcareProfessional;
use Illuminate\Http\Request;

class HealthcareProfessionalController extends Controller
{
    public function index()
    {
        $professionals = HealthcareProfessional::all();
        
        return response()->json([
            'data' => $professionals
        ]);
    }
}
