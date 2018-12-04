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

Route::get('/','StaticPagesController@home')->name('home');
Route::get('/help','StaticPagesController@help')->name('help');
Route::get('/about','StaticPagesController@about')->name('about');

Route::get('signup','UsersController@create')->name('signup');

Route::get('login','SessionsController@create')->name('login');
Route::post('login','SessionsController@store')->name('login');
Route::delete('logout','SessionsController@destroy')->name('logout');


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

