<?php

namespace App\Http\Controllers;

use App\Models\CouponCode;
use Carbon\Carbon;
use Illuminate\Http\Request;

class CouponCodesController extends Controller
{
    public function show($code)
    {
        // 判断优惠卷是否存在
        if (!$record = CouponCode::where('code', $code)->first()) {
            abort(404);
        }
        // 如果优惠卷没有启用， 则等同于优惠卷不存在
        if (!$record->enabled) {
            abort(404);
        }

        if ($record->total - $record->used <= 0) {
            return response()->json(['msg' => '该优惠卷已被兑完'], 403);
        }

        if ($record->no_before && $record->not_before->gt(Carbon::now())) {
            return response()->json(['msg' => '该优惠券现在还不能使用'], 403);
        }

        if ($record->no_after && $record->no_after->lt(Carbon::now())) {
            return response()->json(['msg' => '该优惠卷已过期'], 403);
        }

        return $record;

    }
}
