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
/*
     Route::get('/', function () {
    	return view('welcome');
     });
*/

//主页
Route::get('/','StaticPagesController@home')->name('home');
//帮助页
Route::get('/help','StaticPagesController@help')->name('help');
//关于页
Route::get('/about','StaticPagesController@about')->name('about');

//创建登录页面
Route::get('login','SessionsController@create')->name('login');
//登录
Route::post('login','SessionsController@store')->name('login');
//退出
Route::delete('logout','SessionsController@destroy')->name('logout');

Route::get('password/reset','Auth\ForgotPasswordController@showLinkRequestForm')->name('password.request');
Route::post('password/email', 'Auth\ForgotPasswordController@sendResetLinkEmail')->name('password.email');
Route::get('password/reset/{token}', 'Auth\ResetPasswordController@showResetForm')->name('password.reset');
Route::post('password/reset', 'Auth\ResetPasswordController@reset')->name('password.update');

//关注人列表页面
Route::get('/users/{user}/followings', 'UsersController@followings')->name('users.followings');
//粉丝列表页面
Route::get('/users/{user}/followers', 'UsersController@followers')->name('users.followers');
//关注用户
Route::post('/users/followers/{user}', 'FollowersController@store')->name('followers.store');
//取消关注
Route::delete('/users/followers/{user}', 'FollowersController@destroy')->name('followers.destroy');

//微博创建与删除
Route::resource('statuses','StatusesController',['only'=>['store','destroy']]);

//确认激活邮箱页面
Route::get('signup/confirm/{token}','UsersController@confirmEmail')->name('confirm_email');
//登录页面
Route::get('signup','UsersController@create')->name('signup');
//用户
Route::resource('users', 'UsersController');
/**
 * 资源路由等同于下边
 * Route::get('users','UsersController@index')->name('users.index');   //显示所有用户列表页面
 * Route::get('users/{user}','UsersController@show')->name('users.show');  //显示用户个人信息页面
 * Route::get('users/create','UsersController@create')->name('users.create');  //用户创建信息页面
 * Route::post('users','UsersController@store')->name('users.store');    //创建用户
 * Route::get('users/{user}/edit','UsersController@edit')->name('users.edit');  //编辑用户个人资料页面
 * Route::patch('users/{user}','UsersController@update')->name('users.update');  //更新用户
 * Route::delete('users/{user}','UsersController@destroy')->name('users.destroy'); //删除用户
 */

