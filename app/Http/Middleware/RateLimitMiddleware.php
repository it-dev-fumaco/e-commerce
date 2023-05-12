<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use DB;

class RateLimitMiddleware
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
        $executed = RateLimiter::attempt(
            'send-message: checker',
            $perMinute = 60,
            function() use ($next, $request) {
                return $next($request);
            },
            60
        );
         
        if (!$executed) {
            $checker = DB::table('fumaco_admin_user')->where('username', $request->username)->where('xstatus', 1)->exists();
            if($checker){
                DB::table('fumaco_admin_user')->where('username', $request->username)->update([
                    'xstatus' => 0,
                    'remarks' => 'Locked Out'
                ]);
            }
            abort(401);
        }else{
            session()->flash('success', $request->path());
            return $next($request);
        }
    }
}
