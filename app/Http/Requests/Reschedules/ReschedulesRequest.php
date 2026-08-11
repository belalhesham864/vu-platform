<?php

namespace App\Http\Requests\Reschedules;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class ReschedulesRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'interview_slot_id' => 'sometimes|required|exists:interview_slots,id',
            'date' => 'sometimes|required|date',
            'new_start_time' => 'sometimes|required|date_format:H:i',
            'new_end_time' => 'sometimes|required|date_format:H:i|after:new_start_time',
            'reason' => 'sometimes|required|string|max:255',
            'status' => 'sometimes|required|in:pending,approved,rejected',
            'requested_by' => 'sometimes|required|exists:candidates,id',
        ];
    }
}
