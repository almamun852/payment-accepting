<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Http\Request;

class MockCheck
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
        if ($request->hasHeader("X-Mock-Status")) {
            if (User::where('mock_token', $request->header('X-Mock-Status'))->count() > 0) {
                return $next($request);
            } else {
                return response()->json(['status'=>401,'message'=>'Unauthorized']);
            }
        } else {
             return response()->json(['status'=>401,'message'=>'Mock Status Not Setted.']);
        }
    }
}
