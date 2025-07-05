<?php

namespace App\Http\Requests\Company;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

abstract class CompanyRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'email' => ['nullable', 'email', 'max:255', Rule::unique('companies', 'email')->ignore($this->route('company'))],
            'website' => ['nullable', 'url', 'max:255'],
            'logo' => ['nullable', 'image', 'max:2048'], // Max 2MB
        ];
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'name.required' => 'The company name is required.',
            'email.email' => 'Please enter a valid email address.',
            'email.unique' => 'A company with this email already exists.',
            'website.url' => 'Please enter a valid URL for the website.',
            'logo.image' => 'The logo must be an image.',
            'logo.max' => 'The logo may not be greater than 2MB.',
        ];
    }
}
