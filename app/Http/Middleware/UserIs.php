<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class UserIs
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next, string $rule)
    {
        $user = $request->user();
        if ($user->rules()->where('name', '=', $rule)->exists()) {
            return $next($request);
        } else {
            abort(403, "Access denied, please back to your previous location.");
        }
    }
}
