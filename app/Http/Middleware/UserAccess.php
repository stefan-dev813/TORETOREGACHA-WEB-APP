<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Auth; 

class UserAccess
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next, $userType)
    {
        if (Auth::user() ) {
            if (auth()->user()->getType()=="admin" && $userType=="staff") {
                return $next($request);
            } else {
                if( auth()->user()->getType()== $userType ){
                    return $next($request);
                }
            }
        }
        

        // return inertia('NoPermissionAccess');
        return redirect()->route('login');
    }
}
