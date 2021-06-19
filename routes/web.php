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

// 秒杀商品下单
Route::post('seckill_orders', 'OrdersController@seckill')->name('seckill_orders.store')->middleware('random_drop:80');

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
    // 添加到购物车
    Route::post('cart', 'CartController@add')->name('cart.add');
    // 购物车列表
    Route::get('cart', 'CartController@index')->name('cart.index');
    // 从购物车中移除商品
    Route::delete('cart/{sku}', 'CartController@remove')->name('cart.remove');
    // 添加订单
    Route::post('orders', 'OrdersController@store')->name('orders.store');
    // 订单列表
    Route::get('orders', 'OrdersController@index')->name('orders.index');
    // 订单详情
    Route::get('orders/{order}', 'OrdersController@show')->name('orders.show');
    // 订单支付 alipay
    Route::get('payment/{order}/alipay', 'PaymentController@payByAlipay')->name('payment.alipay');
    // 客户端回调 alipay
    Route::get('payment/alipay/return', 'PaymentController@alipayReturn')->name('payment.alipay.return');
    // 订单支付 微信
    Route::get('payment/{order}/wechat', 'PaymentController@payByWechat')->name('payment.wechat');
    // 确认收货
    Route::post('orders/{order}/received', 'OrdersController@received')->name('orders.received');
    // 用户评价与评分
    Route::get('orders/{order}/review', 'OrdersController@review')->name('orders.review.show');
    Route::post('orders/{order}/review', 'OrdersController@sendReview')->name('orders.review.store');
    // 订单退款
    Route::post('orders/{order}/apply_refund', 'OrdersController@applyRefund')->name('orders.apply_refund');
    // 检查优惠卷
    Route::get('coupon_codes/{code}', 'CouponCodesController@show')->name('coupon_codes.show');
    // 众筹
    Route::post('crowdfunding_orders', 'OrdersController@crowdfunding')->name('crowdfunding_orders.store');
    // 分期付款
    Route::post('payment/{order}/installment', 'PaymentController@payByInstallment')->name('payment.installment');
    // 分期付款列表页
    Route::get('installments', 'InstallmentsController@index')->name('installments.index');
    // 分期付款详情页
    Route::get('installments/{installment}', 'InstallmentsController@show')->name('installments.show');
    // 分期付款支付
    Route::get('installments/{installment}/alipay', 'InstallmentsController@payByAlipay')->name('installments.alipay');
     // 分期付款前端回调
    Route::get('installments/alipay/return', 'InstallmentsController@alipayReturn')->name('installments.alipay.return');
    // 分期付款支付 微信支付
    Route::get('installments/{installment}/wechat', 'InstallmentsController@payByWechat')->name('installments.wechat');


});

// 服务端回调
Route::post('payment/alipay/notify', 'PaymentController@alipayNotify')->name('payment.alipay.notify');
Route::post('payment/wechat/notify', 'PaymentController@wechatNotify')->name('payment.wechat.notify');
// 微信退款回调
Route::post('payment/wechat/refund_notify', 'PaymentController@wechatRefundNotify')->name('payment.wechat.refund_notify');


// 分期付款后端回调 支付宝
Route::post('installments/alipay/notify', 'InstallmentsController@alipayNotify')->name('installments.alipay.notify');
// 分期付款后端回调 微信
Route::post('installments/wechat/notify', 'InstallmentsController@wechatNotify')->name('installments.wechat.notify');
// 微信退款回调
Route::post('installments/wechat/refund_notify', 'InstallmentsController@wechatRefundNotify')->name('installments.wechat.refund_notify');


Route::redirect('/', '/products')->name('root');
Route::get('products', 'ProductsController@index')->name('products.index');
Route::get('products/{product}', 'ProductsController@show')->name('products.show');


