<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AppointmentCompleteRequest extends FormRequest
{
    public function authorize()
    {
        return $this->user()->id === $this->appointment->user_id;
    }

    public function rules()
    {
        return [];
    }
}
