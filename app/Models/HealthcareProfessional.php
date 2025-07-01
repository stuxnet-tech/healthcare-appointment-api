<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HealthcareProfessional extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'specialty'];

    public function appointments()
    {
        return $this->hasMany(Appointment::class);
    }

    public function availableTimeSlots($date)
    {
        // Implementation to check available time slots
    }
}
