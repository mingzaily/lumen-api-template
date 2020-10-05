<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/
// 首页
Route::get('/', function () { return view('welcome'); });
// 授权
Route::post('authorization', 'AuthorizationBaseController@store');
Route::delete('authorization', 'AuthorizationBaseController@destroy');
Route::put('authorization', 'AuthorizationBaseController@update');
// 用户
Route::get('/user', 'UserBaseController@show');
Route::post('/user', 'UserBaseController@store');
