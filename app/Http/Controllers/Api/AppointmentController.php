<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\AppointmentCompleteRequest;
use App\Http\Requests\AppointmentStoreRequest;
use App\Models\Appointment;
use App\Services\AppointmentService;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AppointmentController extends Controller
{
    protected $appointmentService;

    public function __construct(AppointmentService $appointmentService)
    {
        $this->appointmentService = $appointmentService;
    }

    public function index(Request $request): JsonResponse
    {
        $appointments = $this->appointmentService->getUserAppointments($request->user()->id);
        return response()->json(['data' => $appointments]);
    }

    public function store(AppointmentStoreRequest $request): JsonResponse
    {
        try {
            $appointment = $this->appointmentService->bookAppointment(
                $request->user()->id,
                $request->validated()
            );

            return response()->json([
                'message' => 'Appointment booked successfully.',
                'data' => $appointment
            ], 201);
            
        } catch (Exception $e) {
            return response()->json([
                'message' => $e->getMessage()
            ], $e->getCode() === 409 ? 409 : 400);
        }
    }

    public function destroy(Request $request, Appointment $appointment): JsonResponse
    {
        $this->appointmentService->cancelAppointment($request->user()->id, $appointment);
        return response()->json(['message' => 'Appointment cancelled successfully.']);
    }

    public function complete(AppointmentCompleteRequest $request, Appointment $appointment): JsonResponse
    {
        $this->appointmentService->completeAppointment(
            $request->user()->id,
            $appointment
        );

        return response()->json(['message' => 'Appointment marked as completed.']);
    }
}
