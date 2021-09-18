<?php

namespace App\Http\Middleware;

use Closure;
use Log;
use Exception;
use App;
use App\Services\MiddlewareServices;
class CheckSeatSaleId
{
    
    /** @var MiddlewareServices */
    protected $MiddlewareServices;

    /**
     * MiddlewareServices constructor.
     * @param MiddlewareServices $MiddlewareServices
     */
    public function __construct(MiddlewareServices $MiddlewareServices)
    {
        $this->MiddlewareServices = $MiddlewareServices;
    }

    /**
     * STS 2021/09/10 Task 48 No.2 Created
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
            if($request->session()->get('admin_flg')){
                $GLID = $this->MiddlewareServices->getOrderGLID($request->seat_sale_id);
                if($GLID == $request->session()->get('GLID') ){
                    return $next($request);
                }
                Log::info('CheckSeatSaleId');
                App::abort(404);
            }else{
                return $next($request);
            }
        }
        Log::info('CheckSeatSaleId');
        App::abort(404);
    }
}
