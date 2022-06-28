<?php

namespace App\Http\Middleware;

use App\Models\Institution;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckIfInstitutionActive
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
        if(Institution::where('email',Auth::guard('api-institution')->user()->email)->first()->status == 0){
            return response (['message'=>'Email not activate, ask your Facilitator to activate email']);
        }
        return $next($request);
    }
}
