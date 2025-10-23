<?php

namespace App\Modules\Onboarding\Controllers;

use App\Http\Controllers\ApiController;
use App\Models\Company;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class OnboardingController extends ApiController
{
     /**
     * Register a new company and its admin user
     */
    public function onboardCompany(array $data): array
    {
        $user = Auth::user();

        // 1️⃣ Create the company
        $company = Company::create([
            'name' => $data['company_name'],
            'email' => $data['company_email'] ?? $data['email'],
            'address' => $data['address'] ?? null,
            'city_id' => $data['city_id'] ?? null,
            'state_id' => $data['state_id'] ?? null,
            'country_id' => $data['country_id'] ?? null,
            'industry_type' => $data['industry_type'] ?? null,
            'registration_number' => $data['registration_number'] ?? null,
            'slug' => Str::slug($data['company_name']) . '-' . Str::random(5),
            'status' => 'pending',
            'user_id' => $user->id
        ]);

        return [
            'company' => $company,
        ];
    }
}
