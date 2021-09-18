<?php

namespace App\Http;

use Illuminate\Foundation\Http\Kernel as HttpKernel;

class Kernel extends HttpKernel
{
    /**
     * The application's global HTTP middleware stack.
     *
     * These middleware are run during every request to your application.
     *
     * @var array
     */
    protected $middleware = [
        \Illuminate\Foundation\Http\Middleware\CheckForMaintenanceMode::class,
        \Illuminate\Foundation\Http\Middleware\ValidatePostSize::class,
        \App\Http\Middleware\TrimStrings::class,
        \Illuminate\Foundation\Http\Middleware\ConvertEmptyStringsToNull::class,
        \App\Http\Middleware\TrustProxies::class,
    ];

    /**
     * The application's route middleware groups.
     *
     * @var array
     */
    protected $middlewareGroups = [
        'web' => [
            \App\Http\Middleware\EncryptCookies::class,
            \Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse::class,
            \Illuminate\Session\Middleware\StartSession::class,
            // \Illuminate\Session\Middleware\AuthenticateSession::class,
            \Illuminate\View\Middleware\ShareErrorsFromSession::class,
            \App\Http\Middleware\VerifyCsrfToken::class,
            \Illuminate\Routing\Middleware\SubstituteBindings::class,
        ],

        'api' => [
			\App\Http\Middleware\EncryptCookies::class, // STS 2021/09/09 Task 48 No.2
            \Illuminate\Session\Middleware\StartSession::class,// STS 2021/09/09 Task 48 No.2
            'throttle:60,1',
            'bindings',
        ],
    ];

    /**
     * The application's route middleware.
     *
     * These middleware may be assigned to groups or used individually.
     *
     * @var array
     */
    protected $routeMiddleware = [
        'auth' => \Illuminate\Auth\Middleware\Authenticate::class,
        'auth.basic' => \Illuminate\Auth\Middleware\AuthenticateWithBasicAuth::class,
        'bindings' => \Illuminate\Routing\Middleware\SubstituteBindings::class,
        'can' => \Illuminate\Auth\Middleware\Authorize::class,
        'guest' => \App\Http\Middleware\RedirectIfAuthenticated::class,
        'throttle' => \Illuminate\Routing\Middleware\ThrottleRequests::class,
        'check_performance_id' => \App\Http\Middleware\CheckPerformanceId::class,
        'check_schedule_id' => \App\Http\Middleware\CheckScheduleId::class,
        'check_had_login' => \App\Http\Middleware\CheckHadLogin::class,
        'check_login' => \App\Http\Middleware\CheckLogin::class,
        'profile_authority' => \App\Http\Middleware\CheckProfileAuthority::class,
        'event_info_authority' => \App\Http\Middleware\CheckEventInfoAuthority::class,
        'sales_info_authority' => \App\Http\Middleware\CheckSalesInfoAuthority::class,
        'personal_info_authority' => \App\Http\Middleware\CheckPersonalInfoAuthority::class,
        'check_admin_permission' => \App\Http\Middleware\CheckAdminPermission::class,
		'event_info_authority_fault_access' => \App\Http\Middleware\CheckEventInfoAuthorityFaultAccess::class,// STS 2021/09/06 Task 48 No.2
		'check_seat_sale_id' => \App\Http\Middleware\CheckSeatSaleId::class,// STS 2021/09/09 Task 48 No.2
    ];
}
