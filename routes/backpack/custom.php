<?php

use Illuminate\Contracts\Session\Session as ContractsSessionSession;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Session;
use Symfony\Component\HttpFoundation\Session\Session as SessionSession;


// --------------------------
// Custom Backpack Routes
// --------------------------
// This route file is loaded automatically by Backpack\Base.
// Routes you generate using Backpack\Generators will be placed here.

Route::group([
    'prefix'     => config('backpack.base.route_prefix', 'admin'),
    'middleware' => array_merge(
        (array) config('backpack.base.web_middleware', 'web'),
        (array) config('backpack.base.middleware_key', 'admin')
    ),
    'namespace'  => 'App\Http\Controllers\Admin',
], function () { // custom admin routes
    Route::crud('orders', 'OrdersCrudController');
    Route::get('orders-make-archived/{id}', 'OrdersCrudController@makeOrderArchived');
    Route::put('editable-column/{id}', 'OrdersCrudController@updateCulumns');
    Route::crud('status', 'StatusCrudController');
    Route::crud('order-status', 'OrderStatusCrudController');
    Route::crud('complexity', 'ComplexityCrudController');
    Route::crud('currency', 'CurrencyCrudController');
    Route::crud('telegram-config', 'TelegramConfigCrudController');
    Route::get('lang/{locale}', 'LanguageController@switchLanguage');
}); // this should be the absolute last line of this file