<?php

namespace App\Modules\Onboarding\Services;

use App\Http\Resources\UserResource;
use App\Models\Company;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class AuthenticationService
{


    /**
     * Register a new user with email and password
     */
    public function register(array $data)
    {
        $user = User::create(array_merge($data, ['provider' => 'local']));
        $user->assignRole('user');

        $token = $user->createToken('auth_token')->plainTextToken;

        return [
            'user' => new UserResource($user),
            'token' => $token,
        ];
    }

    public function registerCompanyUser(array $data)
    {
        $user = User::create(array_merge($data, ['provider' => 'local']));
        $user->assignRole('company');

        $token = $user->createToken('company_auth_token')->plainTextToken;

        return [
            'user' => new UserResource($user),
            'token' => $token,
        ];
    }


    /**
     * Login a company admin
     */
    public function login(array $credentials): array
    {
        $user = User::where('email', $credentials['email'])->first();

        if (!$user || !Hash::check($credentials['password'], $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['The provided credentials are incorrect.'],
            ]);
        }

        $token = $user->createToken('company_auth_token')->plainTextToken;

        return [
            'message' => 'Login successful',
            'user' => new UserResource($user),
            'token' => $token,
        ];
    }

    /**
     * Logout the current authenticated user
     */
    public function logout($user): void
    {
        $user->currentAccessToken()->delete();
    }

    /**
     * Get authenticated user details
     */
    public function me($user): array
    {
        return [
            'user' => $user->load('company'),
        ];
    }


    /**
     * Handle Google login/signup
     */
    public function handleGoogleAuth($googleUser)
    {
        $user = User::where('email', $googleUser->getEmail())->first();

        if (!$user) {
            $user = User::create([
                'name' => $googleUser->getName(),
                'email' => $googleUser->getEmail(),
                'google_id' => $googleUser->getId(),
                'provider' => 'google',
                'password' => Str::random(12),
            ]);
        }

        $token = $user->createToken('google_auth_token')->plainTextToken;

        return [
            'user' => new UserResource($user),
            'token' => $token,
        ];
    }
}
