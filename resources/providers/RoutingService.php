<?php

namespace Com\Detalhe\Core\Services;

use Themosis\Facades\Route;
use Themosis\Foundation\ServiceProvider;

class RoutingService extends ServiceProvider
{
    /**
     * Register plugin routes.
     * Define a custom namespace.
     */
    public function register()
    {
        Route::group([
            'namespace' => 'Com\Detalhe\Core\Controllers'
        ], function () {
            require themosis_path('plugin.com.detalhe.core.resources').'routes.php';
        });
    }
}