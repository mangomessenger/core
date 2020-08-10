<?php

namespace App\Http\Controllers;

use App\Exceptions\Auth\AuthRequestExpiredException;
use App\Exceptions\Auth\PhoneCodeHashInvalidException;
use App\Exceptions\Auth\PhoneCodeInvalidException;
use App\Exceptions\Auth\PhoneNumberOccupiedException;
use App\Exceptions\Auth\PhoneNumberUnoccupiedException;
use App\Exceptions\JWT\FingerprintInvalidException;
use App\Exceptions\JWT\RefreshTokenInvalidException;
use App\Http\Requests\Auth\LogoutRequest;
use App\Http\Requests\Auth\RefreshTokenRequest;
use App\Http\Requests\Auth\SendCodeRequest;
use App\Http\Requests\Auth\SignInRequest;
use App\Http\Requests\Auth\SignUpRequest;
use App\Http\Resources\AuthRequestResource;
use App\Http\Resources\SessionResource;
use App\Http\Resources\TokensResource;
use App\Services\Auth\AuthService;
use Exception;
use Illuminate\Http\JsonResponse;

class AuthController extends Controller
{
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
        $authRequest = $this->authService->sendCode($request->validated());

        return new AuthRequestResource($authRequest);
    }

    /**
     * Sending a code.
     * @param SignUpRequest $request
     * @return SessionResource
     *
     * @throws AuthRequestExpiredException
     * @throws PhoneCodeHashInvalidException
     * @throws PhoneCodeInvalidException
     * @throws PhoneNumberOccupiedException
     */
    public function signUp(SignUpRequest $request)
    {
        $result = $this->authService->signUp($request->validated(), $request);

        return new SessionResource($result['session'], $result['access_token']);
    }

    /**
     * Sending a code.
     * @param SignInRequest $request
     * @return SessionResource
     *
     * @throws AuthRequestExpiredException
     * @throws PhoneCodeHashInvalidException
     * @throws PhoneCodeInvalidException
     * @throws PhoneNumberUnoccupiedException
     */
    public function signIn(SignInRequest $request)
    {
        $result = $this->authService->signIn($request->validated(), $request);

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
        $result = $this->authService->refreshTokens($request->validated(), $request);

        return new TokensResource($result['session'], $result['access_token']);
    }

    /**
     * Logout from session.
     *
     * @param LogoutRequest $request
     * @return JsonResponse
     *
     * @throws RefreshTokenInvalidException
     */
    public function logout(LogoutRequest $request)
    {
        $this->authService->logout($request->validated());

        return response()->json([], 204);
    }
}
