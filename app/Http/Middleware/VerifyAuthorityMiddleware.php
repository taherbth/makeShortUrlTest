<?php

namespace App\Http\Middleware;

use App\Models\Authority;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class VerifyAuthorityMiddleware
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
        if(Authority::where('email',Auth::guard('api-authority')->user()->email)->first() && Auth::user()->token()->role==4) return $next($request);
        else {
            return response(['message'=>'Unauthenticated'],401);
        }
    }
}
