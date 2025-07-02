<?php

namespace Tests\Feature;

use App\Models\Appointment;
use App\Models\HealthcareProfessional;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use PHPUnit\Framework\Attributes\Test;

class AppointmentTest extends TestCase
{
    use RefreshDatabase;

    protected User $user;
    protected HealthcareProfessional $professional;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create();
        $this->professional = HealthcareProfessional::factory()->create();
    }

    #[Test]
    public function user_can_book_appointment()
    {
        $startTime = now()->addDay()->setTime(10, 0);
        $endTime = $startTime->copy()->addHour();

        $response = $this->actingAs($this->user)->postJson('/api/appointments', [
            'healthcare_professional_id' => $this->professional->id,
            'appointment_start_time' => $startTime->toDateTimeString(),
            'appointment_end_time' => $endTime->toDateTimeString(),
        ]);

        $response->assertStatus(201)
            ->assertJsonStructure([
                'message',
                'data' => [
                    'id',
                    'user_id',
                    'healthcare_professional_id',
                    'appointment_start_time',
                    'appointment_end_time',
                    'status'
                ]
            ])
            ->assertJson([
                'message' => 'Appointment booked successfully.',
                'data' => [
                    'user_id' => $this->user->id,
                    'healthcare_professional_id' => $this->professional->id,
                    'status' => 'booked'
                ]
            ]);

        $this->assertDatabaseHas('appointments', [
            'user_id' => $this->user->id,
            'healthcare_professional_id' => $this->professional->id,
            'status' => 'booked'
        ]);
    }

    #[Test]
    public function user_cannot_double_book_time_slot()
    {
        $startTime = now()->addDay()->setTime(10, 0);
        $endTime = $startTime->copy()->addHour();

        Appointment::create([
            'user_id' => $this->user->id,
            'healthcare_professional_id' => $this->professional->id,
            'appointment_start_time' => $startTime,
            'appointment_end_time' => $endTime,
            'status' => 'booked'
        ]);

        $response = $this->actingAs($this->user)->postJson('/api/appointments', [
            'healthcare_professional_id' => $this->professional->id,
            'appointment_start_time' => $startTime->copy()->addMinutes(30)->toDateTimeString(),
            'appointment_end_time' => $endTime->copy()->addMinutes(30)->toDateTimeString(),
        ]);

        $response->assertStatus(409)
            ->assertExactJson([
                'message' => 'The selected time slot is not available.'
            ]);

        $this->assertDatabaseCount('appointments', 1);
    }

    #[Test]
    public function user_cannot_book_appointment_in_past()
    {
        $response = $this->actingAs($this->user)->postJson('/api/appointments', [
            'healthcare_professional_id' => $this->professional->id,
            'appointment_start_time' => now()->subDay()->toDateTimeString(),
            'appointment_end_time' => now()->subDay()->addHour()->toDateTimeString(),
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['appointment_start_time']);
    }

    #[Test]
    public function end_time_must_be_after_start_time()
    {
        $response = $this->actingAs($this->user)->postJson('/api/appointments', [
            'healthcare_professional_id' => $this->professional->id,
            'appointment_start_time' => now()->addDay()->toDateTimeString(),
            'appointment_end_time' => now()->addDay()->subHour()->toDateTimeString(),
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['appointment_end_time']);
    }
}
