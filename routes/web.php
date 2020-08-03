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

Route::get('/info', function () {
    phpinfo();
});
Route::get('/wx/gettoken','TestController@getToken');
Route::get('/wx/curl','TestController@getCurltoken');
Route::get('/wx/guzzle','TestController@getGuzzleToken');
Route::get('accesstoken','TestController@access_token');//自己设置的access_token
Route::get('/userinfo','TestController@getuserinfo');
Route::post('login','LoginController@login');//登录
Route::post('reg','RegisterController@reg');//注册

Route::get('user/info','TestController@userInfo');
Route::get('hash/hash1','HashController@hash1');
Route::get('hash/hash2','HashController@hash2');
Route::get('goods/index','GoodsController@index');//商品首页
Route::get('goods/show','GoodsController@show');//商品详情
Route::get('goods/blacklist','GoodsController@blacklist');//黑名单
Route::get('goods/usersign','GoodsController@Usersign');//签到
Route::get('goods/test','GoodsController@test')->middleware('token');//验证token
Route::get('goods/test1','GoodsController@test1')->middleware('viewNum');//统计访问次数
Route::get('test/test2','TestController@test2');//加密测试
Route::post('test/dec','TestController@dec');//接收加密信息，并解密
Route::post('test/enc','TestController@enc');//想www发送信息
Route::get('test/header','TestController@headers');//header传输测试
Route::get('test/alipay','TestController@alipay');//支付宝支付





