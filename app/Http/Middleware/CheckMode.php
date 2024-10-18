<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckMode
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */

    public function handle($request, Closure $next, ...$modes): Response
    {
        if ($request->user() && !empty(array_intersect($modes, [$request->user()->mode]))) {
            return $next($request);
        }

        abort(403);
    }
}
