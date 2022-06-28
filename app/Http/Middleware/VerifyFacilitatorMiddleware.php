<?php

namespace App\Http\Middleware;

use App\Models\Facilitator;
use App\Models\Institution;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class VerifyFacilitatorMiddleware
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
        if(Facilitator::where('email',Auth::guard('api-facilitator')->user()->email)->first() && Auth::user()->token()->role==1) return $next($request);
        else {
            return response(['message'=>'Unauthenticated'],401);
        }
    }
}
