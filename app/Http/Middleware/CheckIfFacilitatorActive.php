<?php

namespace App\Http\Middleware;

use App\Models\Facilitator;
use App\Models\Institution;
use App\Models\Student;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckIfFacilitatorActive
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
        $facilitator=Facilitator::where('email',Auth::guard('api-facilitator')->user()->email)->first();
        if($facilitator->status == 0 || Institution::find($facilitator->institution_id)->status == 0){
            return response (['message'=>'Email not activate, ask your institution to activate email']);
        }
        return $next($request);
    }
}
