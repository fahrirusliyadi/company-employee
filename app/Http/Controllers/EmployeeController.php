<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Resources\EmployeeCollection;
use App\Models\Employee;
use App\Http\Requests\Employee\EmployeeIndexRequest;
use App\Http\Requests\Employee\EmployeeStoreRequest;
use App\Http\Requests\Employee\EmployeeUpdateRequest;
use App\Exceptions\ApplicationException;
use App\Notifications\Company\EmployeeAdded;
use Inertia\Inertia;

class EmployeeController extends Controller
{
    /**
     * Display a listing of the employees.
     *
     * @param  \App\Http\Requests\Employee\EmployeeIndexRequest  $request
     * @return \Inertia\Response
     */
    public function index(EmployeeIndexRequest $request)
    {
        $query = Employee::with('company.media');

        // Handle search
        if ($request->has('search') && $request->input('search') !== null) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('first_name', 'like', "%{$search}%")
                    ->orWhere('last_name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        // Handle sorting
        if ($request->has('sort_by') && $request->has('sort_direction')) {
            $query->orderBy($request->input('sort_by'), $request->input('sort_direction'));
        } else {
            // Default sorting by created_at descending
            $query->orderBy('created_at', 'desc');
        }

        // Handle pagination
        $perPage = $request->input('per_page', 10); // Default to 10 if not provided
        $employees = $query->paginate($perPage);

        return Inertia::render('Employee/Index', [
            'employees' => new EmployeeCollection($employees),
            'filters' => $request->only(['page', 'per_page', 'search', 'sort_by', 'sort_direction']),
        ]);
    }

    /**
     * Show the form for creating a new employee.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created employee in storage.
     *
     * @param  \App\Http\Requests\Employee\EmployeeStoreRequest  $request
     * @return \Illuminate\Http\RedirectResponse
     * @throws \App\Exceptions\ApplicationException
     */
    public function store(EmployeeStoreRequest $request)
    {
        try {
            $employee = Employee::create($request->validated());
            $employee->company->notify(new EmployeeAdded($employee, $employee->company));

            return redirect()->route('employees.index')
                ->with('success', 'Employee created successfully.');
        } catch (\Exception $e) {
            throw new ApplicationException('Failed to create employee. Please try again.');
        }
    }

    /**
     * Display the specified employee.
     *
     * @param  \App\Models\Employee  $employee
     * @return \Illuminate\Http\Response
     */
    public function show(Employee $employee)
    {
        //
    }

    /**
     * Show the form for editing the specified employee.
     *
     * @param  \App\Models\Employee  $employee
     * @return \Illuminate\Http\Response
     */
    public function edit(Employee $employee)
    {
        //
    }

    /**
     * Update the specified employee in storage.
     *
     * @param  \App\Http\Requests\Employee\EmployeeUpdateRequest  $request
     * @param  \App\Models\Employee  $employee
     * @return \Illuminate\Http\RedirectResponse
     * @throws \App\Exceptions\ApplicationException
     */
    public function update(EmployeeUpdateRequest $request, Employee $employee)
    {
        try {
            $employee->update($request->validated());

            return redirect()->route('employees.index')
                ->with('success', 'Employee updated successfully.');
        } catch (\Exception $e) {
            throw new ApplicationException('Failed to update employee. Please try again.');
        }
    }

    /**
     * Remove the specified employee from storage.
     *
     * @param  \App\Models\Employee  $employee
     * @return \Illuminate\Http\RedirectResponse
     * @throws \App\Exceptions\ApplicationException
     */
    public function destroy(Employee $employee)
    {
        try {
            $employee->delete();

            return redirect()->route('employees.index')
                ->with('success', 'Employee deleted successfully.');
        } catch (\Exception $e) {
            throw new ApplicationException('Failed to delete employee. Please try again.');
        }
    }
}
