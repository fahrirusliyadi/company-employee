<?php

namespace App\Http\Controllers\Company;

use App\Http\Controllers\Controller;
use App\Http\Resources\CompanyCollection;
use App\Models\Company;
use App\Http\Requests\Company\CompanyIndexRequest;
use App\Http\Requests\Company\CompanyStoreRequest;
use App\Http\Requests\Company\CompanyUpdateRequest;
use App\Exceptions\ApplicationException;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class CompanyController extends Controller
{
    /**
     * Display a listing of the companies.
     *
     * @param  \App\Http\Requests\Company\CompanyIndexRequest  $request
     * @return \Inertia\Response
     */
    public function index(CompanyIndexRequest $request)
    {
        $query = Company::with('media');

        // Handle search
        if ($request->has('search') && $request->input('search') !== null) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            });
        }

        // Handle sorting
        if ($request->has('sort_by') && $request->has('sort_direction')) {
            $query->orderBy($request->input('sort_by'), $request->input('sort_direction'));
        }

        // Handle pagination
        $perPage = $request->input('per_page', 10); // Default to 10 if not provided
        $companies = $query->paginate($perPage);

        return Inertia::render('Company/Index', [
            'companies' => new CompanyCollection($companies),
            'filters' => $request->only(['page', 'per_page', 'search', 'sort_by', 'sort_direction']),
        ]);
    }

    /**
     * Show the form for creating a new company.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created company in storage.
     *
     * @param  \App\Http\Requests\Company\CompanyStoreRequest  $request
     * @return \Illuminate\Http\RedirectResponse
     * @throws \App\Exceptions\ApplicationException
     */
    public function store(CompanyStoreRequest $request)
    {
        try {
            DB::transaction(function () use ($request) {
                $company = Company::create($request->validated());

                if ($request->hasFile('logo')) {
                    $company->addMediaFromRequest('logo')->toMediaCollection('logo');
                }
            });

            return redirect()->route('companies.index')
                ->with('success', 'Company created successfully.');
        } catch (\Exception $e) {
            throw new ApplicationException('Failed to create company. Please try again.');
        }
    }

    /**
     * Display the specified company.
     *
     * @param  \App\Models\Company  $company
     * @return \Illuminate\Http\Response
     */
    public function show(Company $company)
    {
        //
    }

    /**
     * Show the form for editing the specified company.
     *
     * @param  \App\Models\Company  $company
     * @return \Illuminate\Http\Response
     */
    public function edit(Company $company)
    {
        //
    }

    /**
     * Update the specified company in storage.
     *
     * @param  \App\Http\Requests\Company\CompanyUpdateRequest  $request
     * @param  \App\Models\Company  $company
     * @return \Illuminate\Http\RedirectResponse
     * @throws \App\Exceptions\ApplicationException
     */
    public function update(CompanyUpdateRequest $request, Company $company)
    {
        try {
            DB::transaction(function () use ($request, $company) {
                $company->update($request->validated());

                if ($request->hasFile('logo')) {
                    $company->clearMediaCollection('logo');
                    $company->addMediaFromRequest('logo')->toMediaCollection('logo');
                }
            });

            return redirect()->route('companies.index')
                ->with('success', 'Company updated successfully.');
        } catch (\Exception $e) {
            throw new ApplicationException('Failed to update company. Please try again.');
        }
    }

    /**
     * Remove the specified company from storage.
     *
     * @param  \App\Models\Company  $company
     * @return \Illuminate\Http\RedirectResponse
     * @throws \App\Exceptions\ApplicationException
     */
    public function destroy(Company $company)
    {
        try {
            DB::transaction(function () use ($company) {
                $company->delete();
            });

            return redirect()->route('companies.index')
                ->with('success', 'Company deleted successfully.');
        } catch (\Exception $e) {
            throw new ApplicationException('Failed to delete company. Please try again.');
        }
    }
}
