<?php

namespace App\Http\Requests\Auth;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;

class RgisterRequest extends FormRequest
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
            'company_name' => ['required', 'string', 'max:255', 'unique:companies,company_name'],
            'industry' => ['required', 'string', 'max:50'],
            'location' => ['required', 'string', 'max:50'],
            'about' => ['nullable', 'string','max:300'],
            'phone' => ['required', 'string', 'max:20', 'unique:companies,phone'],
            'website' => ['required', 'url', 'max:50'],
            'company_size' => ['required', 'string'],
            'logo' => ['required', 'image', 'mimes:jpg,jpeg,png', 'max:2048'],

            'name' => ['required', 'string', 'max:50'],
            'email' => ['required', 'email', 'max:50', 'unique:users,email'],
            'password' => ['required', 'confirmed',Password::min(8)->letters()->mixedCase()->numbers()],
        ];
    }
     public function messages(): array
    {
        return [
            'company_name.required' => 'Company name is required.',
            'company_name.unique' => 'This company name already exists.',
            'industry.required' => 'industry name is required.',

            'email.unique' => 'This email is already registered.',

            'phone.unique' => 'This phone number is already registered.',

            'logo.image' => 'Logo must be an image.',
            'logo.mimes' => 'Logo must be jpg, jpeg or png.',
            'logo.max' => 'Logo size must not exceed 2 MB.',

            'password.confirmed' => 'Password confirmation does not match.',
        ];
    }
}
