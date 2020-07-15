<?php

namespace App\Http\Controllers;

use App\AuthRequest;
use App\Chat;
use App\Exceptions\TestException;
use App\Exceptions\TimeoutException;
use App\Http\Requests\Auth\SendCodeRequest;
use App\Http\Resources\AuthRequestResource;
use Carbon\Carbon;
use http\Client\Curl\User;

class AuthController extends Controller
{
    private const CODE_SEND_TIMEOUT = 120;

    /**
     * Sending a code.
     *
     * @param SendCodeRequest $request
     * @return AuthRequestResource
     */
    public function sendCode(SendCodeRequest $request)
    {
        $user = \App\User::firstWhere('phone', $request->get('phone'));

        $authRequest = AuthRequest::firstWhere('phone', $request->get('phone'));

        if ($authRequest !== NULL) {
            $expireTime = $authRequest->created_at->addSeconds(self::CODE_SEND_TIMEOUT);
            $now = Carbon::now();

            if ($expireTime > $now) {
                $timeout = $now->diffInSeconds($expireTime);

                throw new TimeoutException("A wait of {$timeout} seconds is required", $timeout);
            } else {
                $authRequest->delete();
            }
        }

        //        $code = CodeService::sendCode($request->get('phone'));

        return new AuthRequestResource(AuthRequest::create([
            'phone' => $request->get('phone'),
            'country_code' => $request->get('country_code'),
            'code' => 22222,// $code
            'timeout' => self::CODE_SEND_TIMEOUT,
            'is_new' => $user === NULL ? true : false,
        ]));
    }
}
