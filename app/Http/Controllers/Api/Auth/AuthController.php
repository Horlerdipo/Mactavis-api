<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegisterRequest;
use App\Services\AuthService;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function __construct(private readonly AuthService $authService)
    {
    }

    public function registerUser(RegisterRequest $request): JsonResponse
    {
        $response = $this->authService->registerUser($request->validated());

        return response()->json([
            'status' => true,
            'message' => 'User created successfully',
            'data' => $response,
        ]);
    }

    /**
     * @throws ValidationException
     */
    public function loginUser(LoginRequest $request): JsonResponse
    {
        $response = $this->authService->loginUser($request->validated());

        return response()->json([
            'status' => true,
            'message' => 'User created successfully',
            'data' => $response,
        ]);
    }
}
