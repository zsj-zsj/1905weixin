<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('phpinfo', function () {
    phpinfo();
});


Route::get('user/create','User\Usercontroller@create');
Route::get('user/rediss','User\Usercontroller@rediss');
Route::get('user/baidu','User\Usercontroller@baidu');

//微信
Route::get('/wx','Weixin\Wxcontroller@checkSignature');
Route::post('/wx','Weixin\Wxcontroller@receiv');   //接受微信推送事件