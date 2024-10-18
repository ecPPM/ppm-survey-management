<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */

    public function handle($request, Closure $next, ...$roles): Response
    {
        if ($request->user() && !empty(array_intersect($roles, [$request->user()->role->name]))) {
            return $next($request);
        }

        abort(403);
    }
}
