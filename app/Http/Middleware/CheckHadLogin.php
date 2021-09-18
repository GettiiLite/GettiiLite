<?php

namespace App\Http\Middleware;

use Closure;

class CheckHadLogin
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
        if($request->session()->exists('account_cd')) {
            return redirect('/notice');
        }else{
            return $next($request);
        }
    }
}
