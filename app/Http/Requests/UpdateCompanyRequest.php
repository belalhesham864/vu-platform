<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class UpdateCompanyRequest extends FormRequest
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
        $id=auth()->user()->company->id;
        return [
            'company_name' => ['sometimes', 'string', 'max:255', 'unique:companies,company_name,'.$id],
            'industry' => ['sometimes', 'string', 'max:50'],
            'location' => ['sometimes', 'string', 'max:50'],
            'about' => ['nullable', 'string','max:300'],
            'phone' => ['sometimes', 'string', 'max:20', 'unique:companies,phone,'.$id],
            'website' => ['sometimes', 'url', 'max:255'],
            'company_size' => ['sometimes', 'string'],
            'logo' => ['nullable', 'image', 'mimes:jpg,jpeg,png', 'max:2048'],
            
        ];
    }
}
