<?php

namespace App\Http\Controllers;

use App\Exceptions\TermsOfServiceNotAcceptedException;
use App\Exceptions\TimeoutException;
use App\Http\Requests\Auth\SendCodeRequest;
use App\Http\Requests\Auth\SignUpRequest;
use App\Http\Resources\AuthRequestResource;
use App\Http\Resources\SessionResource;
use App\Http\Resources\UserResource;
use App\Services\Auth\AuthService;
use App\Session;

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

    /**
     * Sending a code.
     *
     * @param SignUpRequest $request
     * @return SessionResource
     * @throws \App\Exceptions\Auth\AuthRequestExpiredException
     * @throws \App\Exceptions\Auth\PhoneNumberOccupiedException
     * @throws \App\Exceptions\InvalidPayloadException
     */
    public function signUp(SignUpRequest $request)
    {
        $session = $this->authService->signUp($request->validated());

        return new SessionResource($session);
    }

}
