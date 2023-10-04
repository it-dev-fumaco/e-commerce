<?php

namespace App\Http\Middleware;

use DB;
use Closure;
use Illuminate\Http\Request;

class CheckERPStatusMiddleware
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
        try {
            DB::getPdo();
            return $next($request);
        } catch (\Throwable $th) {
            if($request->ajax()){
                return response([
                    'error' => 1,
                    'status' => 0,
                    'success' => 0,
                    'message' => 'An error occured. Please contact IT Department.'
                ]);
            }

            return redirect()->back()->with('error', 'An error occured. Please contact IT Department.');
        }
    }
}
