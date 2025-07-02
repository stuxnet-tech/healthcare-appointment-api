<?php

namespace App\Models;

use App\Traits\HasUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Appointment extends Model
{
    use HasFactory, HasUuid;

    protected $guarded = ['id'];

    protected $casts = [
        'appointment_start_time' => 'datetime',
        'appointment_end_time' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function healthcareProfessional()
    {
        return $this->belongsTo(HealthcareProfessional::class);
    }

    public function canBeCancelled()
    {
        return $this->status === 'booked' && 
               $this->appointment_start_time->diffInHours(now()) > 24;
    }
}
