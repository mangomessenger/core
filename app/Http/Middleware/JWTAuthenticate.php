<?php

namespace App\Http\Middleware;

use App\Exceptions\JWT\TokenAbsentException;
use App\Exceptions\JWT\UserNotFoundException;
use Closure;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
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
     * @throws TokenAbsentException
     * @throws TokenExpiredException
     * @throws UserNotFoundException
     */
    public function handle($request, Closure $next, $optional = null)
    {
        $this->auth->setRequest($request);

        try {
            if (!$user = $this->auth->parseToken('token')->authenticate()) {
                throw new UserNotFoundException();
            }
        } catch (TokenExpiredException $e) {
            throw new \App\Exceptions\JWT\TokenExpiredException();
        } catch (TokenInvalidException $e) {
            throw new \App\Exceptions\JWT\TokenInvalidException();
        } catch (\Tymon\JWTAuth\Exceptions\JWTException $e) {
            if ($optional === null) throw new \App\Exceptions\JWT\TokenAbsentException();
        }

        return $next($request);
    }
}
