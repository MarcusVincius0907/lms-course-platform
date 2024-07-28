<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class AuthMiddleware
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

                return redirect()->back();

            }

        }

        return $next($request);
    }
}
