<?php

namespace App\Http\Middleware;


use App\Models\Institution;
use App\Models\Student;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckIfStudentActive
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
        $student=Student::where('email',Auth::guard('api-student')->user()->email)->first();
        if($student->status == 0 || Institution::find($student->institution_id)->status == 0){
            return response (['message'=>'Email not activate, ask your Facilitator to activate email']);
        }
        return $next($request);
    }
}
