<?php

namespace App\Http\Controllers;

use App\Http\Requests\CartRequest;
use App\Http\Requests\Request;
use App\Models\CartItem;
use App\Models\ProductSku;

class CartController extends Controller
{
    /**
     * 添加到购物车
     */
    public function add(CartRequest $request)
    {
         $user   = $request->user();
         $skuId  = $request->input('sku_id');
         $amount = $request->input('amount');

        // 从数据库中查询该商品是否已经再购物车中
        if ($cart = $user->cartItems()->where('product_sku_id', $skuId)->first()) {
            // 如果存在则直接叠加商品数量
            $cart->update(['amount' => $cart->amount + $amount]);
        } else {

            // 否则创建一个新的购物车记录
            $cart = new CartItem(['amount' => $amount]);
            $cart->user()->associate($user);
            $cart->productSku()->associate($skuId);
            $cart->save();
        }
        return [];
    }

    /**
     * 购物车列表
     */
    public function index(Request $request)
    {
        $cartItems = $request->user()->cartItems()->with(['productSku.product'])->get();

        return view('cart.index', ['cartItems'=> $cartItems]);
    }

    /**
     * 从购物车中移除商品
     */
    public function remove(ProductSku $sku, Request $request)
    {
        $request->user()->cartItems()->whher('product_sku_id', $sku->id)->delete();

        return [];
    }
}
