<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\ApiController;
use App\Services\AuthService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AuthController extends ApiController
{
    protected AuthService $authService;

    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;
    }

    public function register(Request $request): JsonResponse
    {
        $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $user = $this->authService->register($request->all());

        return $this->createdResponse($user, 'Registration successful. Please verify your email.');
    }

    public function verifyOtp(Request $request): JsonResponse
    {
        $request->validate([
            'email' => 'required|email',
            'otp'   => 'required|numeric',
        ]);

        $user = $this->authService->verifyOtp($request->email, $request->otp);

        return $this->successResponse($user, 'OTP verified successfully.');
    }

    public function login(Request $request): JsonResponse
    {
        $request->validate([
            'email'    => 'required|email',
            'password' => 'required|string',
        ]);

        $user = $this->authService->login($request->only('email', 'password'));

        return $this->successResponse($user, 'Login successful.');
    }

    public function logout(): JsonResponse
    {
        $this->authService->logout();

        return $this->successResponse(null, 'Logout successful.');
    }
}