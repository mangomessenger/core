<?php

namespace App\Http\Controllers;

use App\Exceptions\TimeoutException;
use App\Http\Requests\Auth\SendCodeRequest;
use App\Http\Resources\AuthRequestResource;
use App\Services\Auth\AuthRequestService;
use App\Services\Auth\AuthService;

class AuthController extends Controller
{
    private const CODE_SEND_TIMEOUT = 120;

    /**
     * Service for auth
     *
     * @var AuthService $authService
     */
    private AuthService $authService;

    /**
     * AuthController constructor.
     *
     * @param AuthService $authService
     */
    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;
    }

    /**
     * Sending a code.
     *
     * @param SendCodeRequest $request
     * @return AuthRequestResource
     *
     * @throws TimeoutException
     */
    public function sendCode(SendCodeRequest $request)
    {
        $authRequest = $this->authService->sendCode($request->validated(), self::CODE_SEND_TIMEOUT);

        return new AuthRequestResource($authRequest);
    }
}
