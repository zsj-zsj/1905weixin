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

// Route::get('/', function () {
//     return view('welcome');
// });

Route::get('phpinfo', function () {
    phpinfo();
});

Route::get('/','Index\IndexController@index');  //商城首页
Route::get('/index/wxlogin','Index\IndexController@wxlogin');  //商城首页
Route::get('/goods/detail/{id}','Goods\GoodsController@index');  //商品详情

//分享
Route::get('/test/hello',function(){
    $current_url = $_SERVER['APP_URL'] . $_SERVER['REQUEST_URI'];
    echo 'CURRENT_URL: '.$current_url;echo '</br>';
    echo '<pre>';print_r($_SERVER);echo '</pre>';
});



Route::get('user/create','User\Usercontroller@create');
Route::get('user/rediss','User\Usercontroller@rediss');
Route::get('user/baidu','User\Usercontroller@baidu');

//微信
Route::get('/wx/test','Weixin\Wxcontroller@test');
Route::get('/wx','Weixin\Wxcontroller@checkSignature');
Route::post('/wx','Weixin\Wxcontroller@receiv');   //接受微信推送事件
Route::get('/wx/getMedia','Weixin\Wxcontroller@getMedia');  //图片
Route::get('/wx/caidan','Weixin\Wxcontroller@caidan');  //菜单
Route::get('/wx/sendmsg','Weixin\Wxcontroller@sendmsg');  //菜单

Route::get('/vote','Weixin\VoteController@index');   //投票
Route::get('/wx/rwm','Weixin\Wxcontroller@rwm');   //二维码

Route::get('/vote/delkey','Weixin\VoteController@delkey');    //删除rediss








Route::get('/lx/wx','Lianxi\Wx@checkSignature');