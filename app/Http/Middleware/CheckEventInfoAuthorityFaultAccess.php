<?php

namespace App\Http\Middleware;

use Closure;
use Log;
use Exception;
use App;

class CheckEventInfoAuthorityFaultAccess
{
    /**
     * Created STS 2021/09/06 Task 48 No.2 
     *  Handle an incoming request.
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if($request->session()->exists('event_info_flg')){
            if(session('event_info_flg') !== 1 ){
                return $next($request);
            }else{
                Log::info(session('account_cd').' is not event info authority');
                App::abort(404);
            }
        }else{
            Log::info('session is not exists');
            App::abort(404);
        }
    }
}
