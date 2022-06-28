<?php

namespace App\Http\Middleware;

use App\Models\Student;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class VerifyStudentMiddleware
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
        if(Student::where('email',Auth::guard('api-student')->user()->email)->first() && Auth::user()->token()->role==2) return $next($request);
        else {
            return response(['message'=>'Unauthenticated'],401);
        }

    }
}
