<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AppointmentStoreRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'healthcare_professional_id' => 'required|exists:healthcare_professionals,id',
            'appointment_start_time' => 'required|date|after:now',
            'appointment_end_time' => 'required|date|after:appointment_start_time',
        ];
    }
}
