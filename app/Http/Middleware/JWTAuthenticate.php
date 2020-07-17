<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;
use Tymon\JWTAuth\Http\Middleware\BaseMiddleware;

class JWTAuthenticate extends BaseMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param Closure $next
     * @param null $optional
     * @return mixed
     */
    public function handle($request, Closure $next, $optional = null)
    {
        $this->auth->setRequest($request);

        try {
            if (! $user = $this->auth->parseToken('token')->authenticate()) {
                return $this->respondError('JWT error: User not found');
            }
        } catch (\Exception $e) {
            return $this->respondError('JWT error: Token has expired');
        } catch (TokenInvalidException $e) {
            return $this->respondError('JWT error: Token is invalid');
        } catch (JWTException $e) {
            if ($optional === null) {
                return $this->respondError('JWT error: Token is absent');
            }
        }

        return $next($request);

    }

    /**
     * Respond with json error message.
     *
     * @param $message
     * @return \Illuminate\Http\JsonResponse
     */
    protected function respondError($message)
    {
        return response()->json([
            'errors' => [
                'message' => $message,
                'status_code' => 401
            ]
        ], 401);
    }
}
