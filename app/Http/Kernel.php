<?php

namespace App\Http;

use Illuminate\Foundation\Http\Kernel as HttpKernel;

class Kernel extends HttpKernel
{
    /**
     * The application's route middleware aliases.
     *
     * @var array<string, class-string|string>
     */
    protected $middlewareAliases = [
        'cart.user.resolved' => \App\Http\Middleware\EnsureCartUserResolved::class,
        'api.user.auth' => \App\Http\Middleware\ApiUserAuthenticated::class,
    ];
}
