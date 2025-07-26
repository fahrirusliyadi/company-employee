<?php

namespace App\Http\Requests\Company;

use Illuminate\Validation\Rule;

class CompanyStoreRequest extends CompanyRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return array_merge(parent::rules(), [
            'name' => ['required', 'string', 'max:255', Rule::unique('companies', 'name')],
            'email' => ['nullable', 'email', 'max:255', Rule::unique('companies', 'email')],
        ]);
    }
}
