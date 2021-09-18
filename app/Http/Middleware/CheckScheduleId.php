<?php

namespace App\Http\Middleware;

use Closure;
use Log;
use Exception;
use App;
use App\Services\MiddlewareServices;

class CheckScheduleId
{

    /** @var MiddlewareServices */
    protected $MiddlewareServices;

    /**
     * MiddlewareServices constructor.
     * @param MiddlewareServices $UserManagerServices
     */
    public function __construct(MiddlewareServices $MiddlewareServices)
    {
        $this->MiddlewareServices = $MiddlewareServices;
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {        
        //root admin account cheack
        if($request->session()->exists('admin_flg')){
            if(session('admin_flg')){
                $result = $this->MiddlewareServices->checkScheduleId($request->scheduleId);
            }else{
                $result = true;
            }
           
        }

        if($result){
            return $next($request);
        }else{
            Log::info('CheckScheduleId');
            App::abort(404);
        }
    }
}
