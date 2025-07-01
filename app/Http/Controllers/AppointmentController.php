<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\HealthcareProfessional;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class AppointmentController extends Controller
{
    public function index(Request $request)
    {
        $appointments = $request->user()->appointments()
            ->with('healthcareProfessional')
            ->orderBy('appointment_start_time', 'asc')
            ->get();
            
        return response()->json([
            'data' => $appointments
        ]);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'healthcare_professional_id' => 'required|exists:healthcare_professionals,id',
            'appointment_start_time' => 'required|date|after:now',
            'appointment_end_time' => 'required|date|after:appointment_start_time',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        // Check for overlapping appointments
        $isAvailable = HealthcareProfessional::find($request->healthcare_professional_id)
            ->appointments()
            ->where(function ($query) use ($request) {
                $query->whereBetween('appointment_start_time', [
                    $request->appointment_start_time,
                    $request->appointment_end_time
                ])
                ->orWhereBetween('appointment_end_time', [
                    $request->appointment_start_time,
                    $request->appointment_end_time
                ]);
            })
            ->where('status', 'booked')
            ->doesntExist();

        if (!$isAvailable) {
            return response()->json([
                'message' => 'The selected time slot is not available.'
            ], 409);
        }

        $appointment = $request->user()->appointments()->create([
            'healthcare_professional_id' => $request->healthcare_professional_id,
            'appointment_start_time' => $request->appointment_start_time,
            'appointment_end_time' => $request->appointment_end_time,
            'status' => 'booked'
        ]);

        return response()->json([
            'message' => 'Appointment booked successfully.',
            'data' => $appointment
        ], 201);
    }

    public function destroy(Request $request, Appointment $appointment)
    {
        if ($appointment->user_id !== $request->user()->id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        if (!$appointment->canBeCancelled()) {
            return response()->json([
                'message' => 'Appointments can only be cancelled at least 24 hours in advance.'
            ], 422);
        }

        $appointment->update(['status' => 'cancelled']);

        return response()->json(['message' => 'Appointment cancelled successfully.']);
    }

    public function complete(Request $request, Appointment $appointment)
    {
        if ($appointment->user_id !== $request->user()->id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        if ($appointment->status !== 'booked') {
            return response()->json([
                'message' => 'Only booked appointments can be marked as completed.'
            ], 422);
        }

        $appointment->update(['status' => 'completed']);

        return response()->json(['message' => 'Appointment marked as completed.']);
    }
}
