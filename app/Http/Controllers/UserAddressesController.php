<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserAddressRequest;
use App\Models\UserAddress;
use Illuminate\Http\Request;

class UserAddressesController extends Controller
{
    /**
     * 地址列表
     */
    public function index(Request $request)
    {
        return view('user_addresses.index', [
            'addresses' => $request->user()->addresses,
        ]);
    }

    /**
     * 新增收货地址页面
     */
    public function create()
    {
        return view('user_addresses.create_and_edit', ['address' => new UserAddress()]);
    }

    /**
     * 添加收货地址
     */
    public function store(UserAddressRequest $request)
    {
        $request->user()->addresses()->create($request->only([
            'province',
            'city',
            'district',
            'address',
            'zip',
            'contact_name',
            'contact_phone',
        ]));
        return redirect()->route('user_addresses.index');
    }
    /**
     * 编辑收获地址页面
     */
    public function edit(UserAddress $userAddress)
    {
        return view('user_addresses.create_and_edit', ['address' => $userAddress]);
    }

    /**
     * 保存收获地址
     */
    public function update(UserAddressRequest $request, UserAddress $userAddress)
    {
        $userAddress->update($request->only([
            'province',
            'city',
            'district',
            'address',
            'zip',
            'contact_name',
            'contact_phone',
        ]));
        return redirect()->route('user_addresses.index');
    }

    /**
     * 删除收获地址
     */
    public function distroy(UserAddress $userAddress)
    {
        $userAddress->delete();
        return redirect()->route('user_addresses.index');
    }
}
