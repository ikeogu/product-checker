<?php

namespace App\Modules\Company\Controllers;

use App\Http\Controllers\ApiController;
use App\Models\Company;
use App\Modules\Company\Resources\CompanyResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CompanyController extends ApiController
{

    /**
     *  Add Subsidiary
     * @param Request $request
     * @param Company $company
     *
     * @response array< message: string, data: array{child_company: array}>
     */
    public function addSubsidiary(Request $request, Company $company)
    {
        $data = $request->validate([
            'company_name' => 'required|string|max:255',
            'email' => 'nullable|email',
            'phone' => 'nullable|string|max:20',
            'website' => 'nullable|string|max:255',
            'description' => 'nullable|string',
        ]);

        $child = Company::create([
            ...$data,
            'user_id' => Auth::id(),
            'parent_id' => $company->id,
        ]);

        return  $this->successResponse("Child company created successfully", [
            'child_company' => new CompanyResource($child),
        ], Response::HTTP_CREATED);
    }

    /**
     * List Subsidiaries of a Company
     * @param Company $company
     *
     * @response array< message: string, data: array{child_companies: array}>
     */
    public function listSubsidiaries(Company $company)
    {

        $children = $company->subsidiaries()->get();
        return $this->successResponse("Subsidiaries fetched successfully", [
            'child_companies' => CompanyResource::collection($children),
        ], Response::HTTP_OK);
    }
}
