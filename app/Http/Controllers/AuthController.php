<?php

namespace App\Http\Controllers;

use App\Exceptions\JWT\FingerprintInvalidException;
use App\Exceptions\JWT\RefreshTokenInvalidException;
use App\Http\Requests\Auth\RefreshTokenRequest;
use App\Http\Requests\Auth\SendCodeRequest;
use App\Http\Requests\Auth\SignInRequest;
use App\Http\Requests\Auth\SignUpRequest;
use App\Http\Resources\AuthRequestResource;
use App\Http\Resources\SessionResource;
use App\Http\Resources\TokensResource;
use App\Services\Auth\AuthService;
use Exception;
use Illuminate\Support\Facades\Request;

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
     * @throws Exception
     */
    public function sendCode(SendCodeRequest $request)
    {
        $authRequest = $this->authService->sendCode($request->validated(), self::CODE_SEND_TIMEOUT);

        return new AuthRequestResource($authRequest);
    }

    /**
     * Sending a code.
     * @param SignUpRequest $request
     * @return SessionResource
     * @throws Exception
     */
    public function signUp(SignUpRequest $request)
    {
        $result = $this->authService->signUp($request->validated());

        return new SessionResource($result['session'], $result['access_token']);
    }

    /**
     * Sending a code.
     * @param SignInRequest $request
     * @return SessionResource
     * @throws Exception
     */
    public function signIn(SignInRequest $request)
    {
        $result = $this->authService->signIn($request->validated());

        return new SessionResource($result['session'], $result['access_token']);
    }

    /**
     * Sending a code.
     *
     * @param RefreshTokenRequest $request
     * @return TokensResource
     *
     * @throws FingerprintInvalidException
     * @throws RefreshTokenInvalidException
     */
    public function refreshTokens(RefreshTokenRequest $request)
    {
        $result = $this->authService->refreshTokens($request->validated());

        return new TokensResource($result['session'], $result['access_token']);
    }
}
