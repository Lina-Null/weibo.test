<?php

use Illuminate\Support\Facades\Route;

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
//主页
Route::get('/','StaticPagesController@home')->name('home');

//帮助页
Route::get('/help','StaticPagesController@help')->name('help');

//关于页
Route::get('/about','StaticPagesController@about')->name('about');

//注册
Route::get('signup','UsersController@create')->name('signup');

//用户
Route::resource('users','UsersController');
//Route::get('/users/{user}/edit', 'UsersController@edit')->name('users.edit');

//登录退出会话
Route::get('login', 'SessionsController@create')->name('login');
Route::post('login', 'SessionsController@store')->name('login');
Route::delete('logout', 'SessionsController@destroy')->name('logout');

//邮箱确认
Route::get('signup/confirm/{token}', 'UsersController@confirmEmail')->name('confirm_email');
