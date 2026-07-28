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
            'name' => 'required|string',
            'email' => 'required|email',
            'phone' => 'required|string',
            'cv_file' => 'nullable',

            'position_id' => 'required|exists:positions,id',
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
