<?php

namespace App\Services;

use App\Models\Appointment;
use App\Repositories\AppointmentRepository;
use App\Repositories\HealthcareProfessionalRepository;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class AppointmentService
{
    protected $appointmentRepository;
    protected $healthcareProfessionalRepository;

    public function __construct(
        AppointmentRepository $appointmentRepository,
        HealthcareProfessionalRepository $healthcareProfessionalRepository
    ) {
        $this->appointmentRepository = $appointmentRepository;
        $this->healthcareProfessionalRepository = $healthcareProfessionalRepository;
    }

    public function bookAppointment(string $userId, array $data): Appointment
    {
        if (!$this->healthcareProfessionalRepository->isTimeSlotAvailable(
            $data['healthcare_professional_id'],
            $data['appointment_start_time'],
            $data['appointment_end_time']
        )) {
            throw new \Exception('The selected time slot is not available.', 409);
        }

        $appointmentData = array_merge($data, [
            'user_id' => $userId,
            'status' => 'booked'
        ]);

        return $this->appointmentRepository->create($appointmentData);
    }

    public function getUserAppointments(string $userId)
    {
        return $this->appointmentRepository->getUserAppointments($userId);
    }

    public function cancelAppointment(string $userId, Appointment $appointment): void
    {
        if ($appointment->user_id !== $userId) {
            throw new ModelNotFoundException('Appointment not found or unauthorized.');
        }

        if (!$this->appointmentRepository->canBeCancelled($appointment)) {
            throw new \Exception('Appointments can only be cancelled at least 24 hours in advance.', 422);
        }

        $this->appointmentRepository->updateStatus($appointment, 'cancelled');
    }

    public function completeAppointment(string $userId, Appointment $appointment): void
    {
        if ($appointment->user_id !== $userId) {
            throw new ModelNotFoundException('Appointment not found or unauthorized.');
        }

        if ($appointment->status !== 'booked') {
            throw new \Exception('Only booked appointments can be marked as completed.', 422);
        }

        $this->appointmentRepository->updateStatus($appointment, 'completed');
    }
}