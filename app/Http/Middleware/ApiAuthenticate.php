<?php

namespace App\Http\Middleware;

use App\Session;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

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
        $session = Session::firstWhere('access_token_hash', hash('sha256', $request->bearerToken()));

        if (is_null($session)) {
            abort(403);
        }
        Auth::login($session->user);

        return $next($request);

    }
}
