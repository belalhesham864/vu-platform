<?php

namespace App\Http\Requests\Application;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class ApplicationRequest extends FormRequest
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
            'candidate_id' => 'required|exists:candidates,id',
            'position_id' => 'required|exists:positions,id',
            'application_type' => 'required|in:AI Interview,Technical Interview,Final Interview',
            'status' => 'required|in:Under Review,Scheduled,Shortlisted,Accepted,Rejected',
            'decision' => 'nullable|string',
            'decision_date' => 'nullable|date',
            'start_date' => 'nullable|date',
            'ai_score' => 'nullable|numeric|min:0|max:100',
            'flags' => 'nullable|array',
            'flags.*' => 'string',
            'approved_by' => 'nullable|exists:users,id',
        ];
    }
}
