<?php

namespace App\Http\Middleware;

use App\Exceptions\JWT\JWTTokenAbsentException;
use App\Exceptions\JWT\JWTTokenExpiredException;
use App\Exceptions\JWT\JWTTokenInvalidException;
use App\Exceptions\JWT\JWTUserNotFoundException;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;
use Tymon\JWTAuth\Http\Middleware\BaseMiddleware;
use Closure;
use Illuminate\Http\Request;

class JWTAuthenticate extends BaseMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param Closure $next
     * @param null $optional
     * @return mixed
     * @throws JWTTokenAbsentException
     * @throws JWTTokenInvalidException
     * @throws JWTTokenExpiredException
     * @throws JWTUserNotFoundException
     */
    public function handle($request, Closure $next, $optional = null)
    {
        $this->auth->setRequest($request);

        try {
            if (!$user = $this->auth->parseToken('token')->authenticate()) {
                throw new JWTUserNotFoundException();
            }
        } catch (TokenExpiredException $e) {
            throw new JWTTokenExpiredException();
        } catch (TokenInvalidException $e) {
            throw new JWTTokenInvalidException();
        } catch (\Tymon\JWTAuth\Exceptions\JWTException $e) {
            if ($optional === null) throw new JWTTokenAbsentException();
        }

        return $next($request);
    }
}
