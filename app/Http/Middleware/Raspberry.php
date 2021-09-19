<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class Raspberry
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        if (!empty($request->id) && str_contains(config('constants.raspberries'), $request->id) && config('constants.allow_new_rfid')) {
            return $next($request);
        }

        return response()->json(['message' => 'Hardware not allowed'], 419);
    }
}