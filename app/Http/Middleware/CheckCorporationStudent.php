<?php

namespace App\Http\Middleware;

use App\Model\Student;
use Closure;

class CheckCorporationStudent
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        
        //checks if corp id exists
        if(auth()->check()){

            // if the student is a corp student
            if(
                auth()->user()->student &&
                auth()->user()->student->corporation_id != null
            ){

                return $next($request);

            }


            return redirect()->to('/');
        }

        return $next($request);
    }
}
