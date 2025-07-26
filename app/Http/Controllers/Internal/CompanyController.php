<?php

namespace App\Http\Controllers\Internal;

use App\Http\Controllers\Controller;
use App\Http\Requests\Internal\Company\CompanyIndexRequest;
use App\Http\Resources\CompanyCollection;
use App\Models\Company;

class CompanyController extends Controller
{
    /**
     * Get company options for select components.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function options(CompanyIndexRequest $request)
    {
        $query = Company::query();

        // Handle search filtering
        if ($request->has('search') && $request->input('search') !== null) {
            $search = $request->input('search');
            $query->where('name', 'like', "%{$search}%");
        }

        // Order by name for better UX
        $query->orderBy('name');

        // Handle pagination
        $perPage = $request->input('per_page', 20);
        $companies = $query->paginate($perPage);

        return new CompanyCollection($companies);
    }
}
