<?php

namespace App\Http\Requests\Company;

use App\Http\Requests\Company\CompanyRequest;
use Illuminate\Validation\Rule;

class CompanyUpdateRequest extends CompanyRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return array_merge(parent::rules(), [
            'name' => ['required', 'string', 'max:255', Rule::unique('companies', 'name')->ignore($this->route('company'))],
            'email' => ['nullable', 'email', 'max:255', Rule::unique('companies', 'email')->ignore($this->route('company'))],
        ]);
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return array_merge(parent::messages(), [
            'name.unique' => 'A company with this name already exists.',
        ]);
    }
}
