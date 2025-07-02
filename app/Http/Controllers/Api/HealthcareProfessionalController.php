<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Repositories\HealthcareProfessionalRepository;
use Illuminate\Http\JsonResponse;

class HealthcareProfessionalController extends Controller
{
    protected $healthcareProfessionalRepository;

    public function __construct(HealthcareProfessionalRepository $healthcareProfessionalRepository)
    {
        $this->healthcareProfessionalRepository = $healthcareProfessionalRepository;
    }

    public function index(): JsonResponse
    {
        $professionals = $this->healthcareProfessionalRepository->all();
        return response()->json(['data' => $professionals]);
    }
}
