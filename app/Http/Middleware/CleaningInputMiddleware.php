<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CleaningInputMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        $input = $request->all();
        array_walk_recursive($input, function (&$value) {
            $value = is_string($value) ? trim($value) : $value;
        });

        $request->replace($input);

        return $next($request);
    }
}
