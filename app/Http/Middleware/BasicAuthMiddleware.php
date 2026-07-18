<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class BasicAuthMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = config('basicauth.user');
        $pass = config('basicauth.pass');

        if (blank($user) && blank($pass)) {
            return $next($request);
        }

        if ($request->getUser() === $user && $request->getPassword() === $pass) {
            return $next($request);
        }

        return response('Unauthorized.', 401, [
            'WWW-Authenticate' => 'Basic realm="Restricted Area"',
        ]);
    }
}
