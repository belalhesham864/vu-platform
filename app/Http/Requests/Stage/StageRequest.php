<?php

namespace App\Http\Requests\Stage;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class StageRequest extends FormRequest
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
        // $company = Auth::user()->company;
        $company = $this->user()->company;

        return [
            'position_id' => [
                'required',
                Rule::exists('positions', 'id')->where(function ($query) use ($company) {
                    $query->where('company_id', $company->id);
                }),
            ],
            'stage_name' => 'required|string|max:255',
            'stage_order' => 'nullable|integer',
        ];
    }
}
