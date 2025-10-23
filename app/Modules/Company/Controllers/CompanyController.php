<?php

namespace App\Modules\Company\Controllers;

use App\Http\Controllers\ApiController;
use App\Models\Company;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CompanyController extends ApiController
{

    public function addChild(Request $request, Company $company)
    {
        //$this->authorize('manage', $company); // ensure user owns the parent

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

        return response()->json([
            'message' => 'Subsidiary company created successfully',
            'child' => $child,
        ]);
    }
}
