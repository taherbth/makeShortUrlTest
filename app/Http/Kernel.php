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
        \Fruitcake\Cors\HandleCors::class,
        \App\Http\Middleware\TrustHosts::class,
        \App\Http\Middleware\TrustProxies::class,
        \Fruitcake\Cors\HandleCors::class,
        \App\Http\Middleware\PreventRequestsDuringMaintenance::class,
        \Illuminate\Foundation\Http\Middleware\ValidatePostSize::class,
        \App\Http\Middleware\TrimStrings::class,
        \Illuminate\Foundation\Http\Middleware\ConvertEmptyStringsToNull::class,
        \App\Http\Middleware\Cors::class,


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
            'throttle:60,1',
            \Illuminate\Routing\Middleware\SubstituteBindings::class,
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
        'student.auth' => \App\Http\Middleware\StudentProvider::class,
        'CheckIfStudentActive'=>\App\Http\Middleware\CheckIfStudentActive::class,
        'CheckIfStudentInSameInstitute'=>\App\Http\Middleware\CheckIfStudentInSameInstitute::class,
        'VerifyStudentMiddleware'=>\App\Http\Middleware\VerifyStudentMiddleware::class,
        'CheckIfInstitutionOfStudentSubscribed'=>\App\Http\Middleware\CheckIfInstitutionOfStudentSubscribed::class,
//        'student.guest' => \App\Http\Middleware\RedirectIfStudent::class,
//         'student.verified' => \App\Http\Middleware\EnsureStudentEmailIsVerified::class,
//        'student.password.confirm' => \App\Http\Middleware\RequireStudentPassword::class,
        'facilitator.auth' => \App\Http\Middleware\FacilitatorProvider::class,
        'CheckIfFacilitatorActive'=>\App\Http\Middleware\CheckIfFacilitatorActive::class,
        'VerifyFacilitatorMiddleware'=>\App\Http\Middleware\VerifyFacilitatorMiddleware::class,
        'CheckIfInstitutionOfFacilitatorSubscribed'=>\App\Http\Middleware\CheckIfInstitutionOfFacilitatorSubscribed::class,
//        'facilitator.guest' => \App\Http\Middleware\RedirectIfFacilitator::class,
//         'facilitator.verified' => \App\Http\Middleware\EnsureFacilitatorEmailIsVerified::class,
//        'facilitator.password.confirm' => \App\Http\Middleware\RequireFacilitatorPassword::class,
        'institution.auth' => \App\Http\Middleware\InstitutionProvider::class,
        'CheckIfInstitutionActive'=>\App\Http\Middleware\CheckIfInstitutionActive::class,
        'VerifyInstitutionMiddleware'=>\App\Http\Middleware\VerifyInstitutionMiddleware::class,
        'CheckIfInstitutionSubscribed'=>\App\Http\Middleware\CheckIfInstitutionSubscribed::class,
        'CheckIfInstitutionNotSubscribed'=>\App\Http\Middleware\CheckIfInstitutionNotSubscribed::class,
//        'institution.guest' => \App\Http\Middleware\RedirectIfInstitution::class,
//         'institution.verified' => \App\Http\Middleware\EnsureInstitutionEmailIsVerified::class,
//        'institution.password.confirm' => \App\Http\Middleware\RequireInstitutionPassword::class,
        'VerifyAuthorityMiddleware'=>\App\Http\Middleware\VerifyAuthorityMiddleware::class,
        'auth' => \App\Http\Middleware\Authenticate::class,
        'auth.basic' => \Illuminate\Auth\Middleware\AuthenticateWithBasicAuth::class,
        'cache.headers' => \Illuminate\Http\Middleware\SetCacheHeaders::class,
        'can' => \Illuminate\Auth\Middleware\Authorize::class,
        'guest' => \App\Http\Middleware\RedirectIfAuthenticated::class,
        'password.confirm' => \Illuminate\Auth\Middleware\RequirePassword::class,
        'signed' => \Illuminate\Routing\Middleware\ValidateSignature::class,
        'throttle' => \Illuminate\Routing\Middleware\ThrottleRequests::class,
        'verified' => \Illuminate\Auth\Middleware\EnsureEmailIsVerified::class,
        'json.response' => \App\Http\Middleware\ForceJsonResponse::class,
        'cors' => \App\Http\Middleware\Cors::class,
    ];
}
