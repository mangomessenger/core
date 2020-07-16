<?php

namespace App\Http\Middleware;

use App\Session;
use Closure;
use Illuminate\Http\Request;

class ApiAuthenticate
{
    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $session = Session::firstWhere('access_token', $request->bearerToken());
        if (is_null($session)) {
            abort(403);
        } else {
            \Illuminate\Support\Facades\Auth::login($session->user);
        }

        return $next($request);
    }
}
