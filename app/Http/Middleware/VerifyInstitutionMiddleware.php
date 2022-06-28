<?php

namespace App\Http\Middleware;

use App\Models\Institution;
use App\Models\Student;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class VerifyInstitutionMiddleware
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
        if(Institution::where('email',Auth::guard('api-institution')->user()->email)->first() && Auth::user()->token()->role==3) return $next($request);
        else {
            return response(['message'=>'Unauthenticated'],401);
        }

    }
}
