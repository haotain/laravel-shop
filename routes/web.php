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


// 增加verify 参数 邮箱验证
Auth::routes(['verify' => true]);


// auth 中间件代表需要登录, verified 中间件代表需要经过邮箱验证
Route::group(['middleware' => ['auth', 'verified']], function() {
    // 收货地址列表
    Route::get('user_addresses', 'UserAddressesController@index')->name('user_addresses.index');
    // 收货地址页面
    Route::get('user_addresses/create', 'UserAddressesController@create')->name('user_addresses.create');
    // 添加收货地址
    Route::post('user_addresses', 'UserAddressesController@store')->name('user_addresses.store');
    // 编辑收获地址页面
    Route::get('user_addresses/edit/{user_address}', 'UserAddressesController@edit')->name('user_addresses.edit');
    // 编辑收获地址
    Route::put('user_addresses/{user_address}', 'UserAddressesController@update')->name('user_addresses.update');
    // 删除收获地址
    Route::delete('user_addresses/{user_address}', 'UserAddressesController@destroy')->name('user_addresses.delete');

    // 收藏商品
    Route::post('products/{product}/favorite', 'ProductsController@favor')->name('products.favor');
    // 删除收藏
    Route::delete('products/{product}/favorite', 'ProductsController@disfavor')->name('products.disfavor');
    // 收藏列表
    Route::get('products/favorites', 'ProductsController@favorites')->name('products.favorites');


});

Route::redirect('/', '/products')->name('root');
Route::get('products', 'ProductsController@index')->name('products.index');
Route::get('products/{product}', 'ProductsController@show')->name('products.show');


