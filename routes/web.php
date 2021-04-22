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

// Route::get('/', 'PagesController@root')->name('root')->middleware('verified');
Route::get('/', 'PagesController@root')->name('root');

// auth 中间件代表需要登录, verified 中间件代表需要经过邮箱验证
Route::group(['middleware' => ['auth', 'verified']], function() {
    // 收货地址列表
    Route::get('user_addresses', 'UserAddressesController@index')->name('user_addresses.index');
    // 收货地址页面
    Route::get('user_addresses/create', 'UserAddressesController@create')->name('user_addresses.create');
    // 添加收货地址
    Route::post('user_addresses', 'UserAddressesController@store')->name('user_addresses.store');


});

// 增加verify 参数 邮箱验证
Auth::routes(['verify' => true]);

