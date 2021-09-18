<?php

namespace App\Http\Middleware;

use Closure;
use Log;
use Exception;
use App\Services\MiddlewareServices;

class CheckLogin
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
        if($request->session()->exists('account_cd')) {
            // dd($request->session());
            $result = $this->MiddlewareServices->checkAccStatus($request->session()->get('account_cd'));
            if ($result) {
                return $next($request);
            }
            else {
                return redirect('/login');
            }
            
        }else{
            return redirect('/login');
        }
    }
}
