<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Contracts\Support\Responsable;

class TimeSlotUnavailableException extends \Exception implements Responsable
{
    public function toResponse($request)
    {
        return response()->json([
            'message' => $this->getMessage() ?: 'The selected time slot is not available.',
            'errors' => [
                'time_slot' => ['The selected time slot is already booked. Please choose another.']
            ]
        ], 409);
    }
}