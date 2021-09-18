<?php

namespace App\Http\Middleware;

use Closure;
use Log;
use Exception;
use App;
use App\Services\MiddlewareServices;

class CheckPerformanceId
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
        if($request->session()->exists('admin_flg')){
            $performanceId = $request->performanceId ? $request->performanceId : $request->performance_id ;
            if(session('admin_flg')){
                $result = $this->MiddlewareServices->checkPerformanceId($performanceId);
            }else{
                $result = $this->MiddlewareServices->checkSuperUserPerformanceId($performanceId);
            }
           
        }

        if($result){
            return $next($request);
        }else{
            Log::info('CheckPerformanceId:handle');
            App::abort(404);
        }
    }
}
