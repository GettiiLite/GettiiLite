<?php

namespace App\Http\Middleware;

use App;
use Closure;
use Log;
use Exception;

class CheckAdminPermission
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
        if($request->session()->exists('admin_flg')) {
            if ($request->session()->get('admin_flg') == 0) {
                return $next($request);
            }
            else {
                Log::info('CheckAdminPermission');
                App::abort(404);
            }
        }else{
            Log::info('CheckAdminPermission');
            App::abort(404);
        }
    }
}
