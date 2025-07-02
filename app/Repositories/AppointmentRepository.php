<?php

namespace App\Repositories;

use App\Models\Appointment;

class AppointmentRepository
{
    public function create(array $data): Appointment
    {
        return Appointment::create($data);
    }

    public function getUserAppointments(string $userId)
    {
        return Appointment::where('user_id', $userId)
            ->with('healthcareProfessional')
            ->orderBy('appointment_start_time', 'asc')
            ->get();
    }

    public function updateStatus(Appointment $appointment, string $status): bool
    {
        return $appointment->update(['status' => $status]);
    }

    public function canBeCancelled(Appointment $appointment): bool
    {
        return $appointment->status === 'booked' && 
               $appointment->appointment_start_time > now()->addHours(24);
    }
}