<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Auth\Middleware\Authenticate as Middleware;

class Authenticate extends Middleware
{
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return string|null
     */
    public function handle($request, Closure $next, ...$guards): ?string
    {
        try {
            $this->authenticate($request, $guards);
        }
        catch (AuthenticationException $exception) {
            return response('Unauthenticated', 401)
                ->header('Content-Type', 'text/plain');
        }

        return $next($request);
    }
}
