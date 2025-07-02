<?php

namespace App\Models;

use App\Traits\HasUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HealthcareProfessional extends Model
{
    use HasFactory, HasUuid;

    protected $guarded = ['id'];

    public function appointments()
    {
        return $this->hasMany(Appointment::class);
    }

    public function availableTimeSlots($date)
    {
        // Implementation to check available time slots
    }
}
