<?php

namespace Tests\Feature;

use App\Models\HealthcareProfessional;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AppointmentTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_book_appointment()
    {
        $user = User::factory()->create();
        $professional = HealthcareProfessional::factory()->create();

        $response = $this->actingAs($user)->postJson('/api/appointments', [
            'healthcare_professional_id' => $professional->id,
            'appointment_start_time' => now()->addDay()->toDateTimeString(),
            'appointment_end_time' => now()->addDay()->addHour()->toDateTimeString(),
        ]);

        $response->assertStatus(201)
            ->assertJson([
                'message' => 'Appointment booked successfully.',
            ]);
    }

    public function test_user_cannot_double_book_time_slot()
    {
        $user = User::factory()->create();
        $professional = HealthcareProfessional::factory()->create();
        
        $startTime = now()->addDay();
        $endTime = now()->addDay()->addHour();
        
        $professional->appointments()->create([
            'user_id' => $user->id,
            'appointment_start_time' => $startTime,
            'appointment_end_time' => $endTime,
            'status' => 'booked'
        ]);

        $response = $this->actingAs($user)->postJson('/api/appointments', [
            'healthcare_professional_id' => $professional->id,
            'appointment_start_time' => $startTime->addMinutes(30)->toDateTimeString(),
            'appointment_end_time' => $endTime->addMinutes(30)->toDateTimeString(),
        ]);

        $response->assertStatus(409)
            ->assertJson([
                'message' => 'The selected time slot is not available.'
            ]);
    }
}