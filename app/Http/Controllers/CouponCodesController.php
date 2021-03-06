<?php

namespace App\Http\Controllers;

use App\Models\CouponCode;
use Carbon\Carbon;
use Illuminate\Http\Request;

class CouponCodesController extends Controller
{
    public function show($code, Request $request)
    {
        // 判断优惠卷是否存在
        if (!$record = CouponCode::where('code', $code)->first()) {
            abort(404);
        }
        $record->checkAvailabel($request->user());

        return $record;

    }
}
