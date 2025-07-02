<?php

namespace App\Repositories;

use App\Models\HealthcareProfessional;

class HealthcareProfessionalRepository
{
    public function all()
    {
        return HealthcareProfessional::all();
    }

    public function find(int $id): ?HealthcareProfessional
    {
        return HealthcareProfessional::find($id);
    }

    public function isTimeSlotAvailable(int $professionalId, string $startTime, string $endTime): bool
    {
        return HealthcareProfessional::find($professionalId)
            ->appointments()
            ->where(function ($query) use ($startTime, $endTime) {
                $query->whereBetween('appointment_start_time', [$startTime, $endTime])
                    ->orWhereBetween('appointment_end_time', [$startTime, $endTime]);
            })
            ->where('status', 'booked')
            ->doesntExist();
    }
}