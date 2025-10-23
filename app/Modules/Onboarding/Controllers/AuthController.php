<?php

namespace App\Modules\Onboarding\Controllers;

use App\Http\Controllers\ApiController;
use App\Modules\Onboarding\Services\AuthenticationService;
use Illuminate\Http\Request;
use Laravel\Socialite\Facades\Socialite;
use Symfony\Component\HttpFoundation\Response;

class AuthController extends ApiController
{
    public function __construct(protected readonly AuthenticationService $authService) {}

    /**
     * Register User
     *
     * @param Request $request
     */
    public function register(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6',
        ]);

        return $this->successResponse($this->authService->register($data), Response::HTTP_CREATED);
    }

    /**
     * Register Company User
     *  @param Request $request
     */
    public function registerCompanyUser(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6',
        ]);

        return $this->successResponse($this->authService->registerCompanyUser($data), Response::HTTP_CREATED);
    }
    /**
     * Login User
     */
    public function login(Request $request)
    {
        $data = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        try {
            return $this->successResponse(
                $this->authService->login($data),
                200
            );
        } catch (\Exception $e) {
            return $this->error("Something went wrong", Response::HTTP_BAD_REQUEST);
        }
    }

    /*  public function googleRedirect()
    {
        return Socialite::driver('google')->stateless()->redirect();
    }

    public function googleCallback()
    {
        $googleUser = Socialite::driver('google')->stateless()->user();

        $response = $this->authService->handleGoogleAuth($googleUser);

        return $this->successResponse($response, Response::HTTP_OK);
    } */
}
