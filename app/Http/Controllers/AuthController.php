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
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    private const CODE_SEND_TIMEOUT = 120;

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
        //Retrieving user
        $user = \App\User::firstWhere('phone', $request->get('phone'));

        //Retrieving auth request
        $authRequest = AuthRequest::firstWhere('phone', $request->get('phone'));

        //Checking if phone number had auth attempt before
        if ($authRequest !== NULL) {
            $expireTime = $authRequest->created_at->addSeconds(self::CODE_SEND_TIMEOUT);
            $now = Carbon::now();

            // Checks if auth request is in timeout
            if ($expireTime > $now) {
                $timeout = $now->diffInSeconds($expireTime);

                throw new TimeoutException("A wait of {$timeout} seconds is required", $timeout);
            } else {
                //Deletes auth request if one exists
                $authRequest->delete();
            }
        }

        //Generating code
        $code = rand(10000, 99999);
        //        CodeService::sendCode($request->get('phone'));

        //Creating AuthRequest and returning AuthRequestResource
        return new AuthRequestResource(AuthRequest::create([
            'phone' => $request->get('phone'),
            'country_code' => $request->get('country_code'),
            'phone_code_hash' => Hash::make($code),// $code
            'timeout' => self::CODE_SEND_TIMEOUT,
            'is_new' => $user === NULL,
        ]), $code);
    }
}
