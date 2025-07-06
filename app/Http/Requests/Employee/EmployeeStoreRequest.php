<?php

namespace App\Http\Requests\Employee;

use App\Http\Requests\Employee\EmployeeRequest;
use Illuminate\Validation\Rule;

class EmployeeStoreRequest extends EmployeeRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return array_merge(parent::rules(), [
            'email' => ['nullable', 'email', 'max:255', Rule::unique('employees', 'email')],
        ]);
    }
}
