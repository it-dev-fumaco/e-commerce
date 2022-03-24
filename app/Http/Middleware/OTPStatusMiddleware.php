<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Auth;
class OTPStatusMiddleware
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
        if(Auth::check()){
                // return $next($request);
            if(Auth::user()->otp_status == 1){
                return $next($request);
            }else{
                return redirect('/admin/verify');

            }
        }else{
            return redirect('/admin/login');
        }
    }
}
