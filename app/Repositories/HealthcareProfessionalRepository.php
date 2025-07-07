<?php

namespace App\Repositories;

use App\Models\HealthcareProfessional;

class HealthcareProfessionalRepository
{
    public function all()
    {
        return HealthcareProfessional::all();
    }

    public function isTimeSlotAvailable(string $professionalId, string $startTime, string $endTime): bool
    {
        return HealthcareProfessional::find($professionalId)
            ->appointments()
            ->where(function ($query) use ($startTime, $endTime) {
                $query->where('appointment_start_time', '<', $endTime)
                    ->where('appointment_end_time', '>', $startTime);
            })
            ->where('status', 'booked')
            ->doesntExist();
    }
}