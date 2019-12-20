<?php

use Illuminate\Routing\Router;

Admin::routes();

Route::group([
    'prefix'        => config('admin.route.prefix'),
    'namespace'     => config('admin.route.namespace'),
    'middleware'    => config('admin.route.middleware'),
], function (Router $router) {

    $router->get('/', 'HomeController@index')->name('admin.home');
    $router->resource('users', UserController::class);
    $router->resource('img', WxImg::class);
    $router->resource('msg', WxMsg::class);
    $router->resource('voice', WxgVoice::class);
    $router->resource('goods', GoodsController::class);
});
