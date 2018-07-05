<?php

namespace App\Http\Controllers;

use App\Models\CouponCode;
use Illuminate\Http\Request;

class CouponCodesController extends Controller
{
    //
    public function show($code)
    {
        // 判断优惠券是否存在
        if (!$record = CouponCode::where('code', $code)->where('enabled', true)->first())
        {
            abort(404);
        }

        if ($record->total - $record->used <= 0)
        {
            return response()->json(['msg' => '该优惠券已被兑完'], 403);
        }

        if ($record->not_before && $record->not_before->gt(now()))
        {
            return response()->json(['msg' => '该优惠券现在还不能使用'], 403);
        }

        if ($record->not_after && $record->not_after->lt(now()))
        {
            return response()->json(['msg' => '该优惠券已过期'], 403);
        }

        return $record;
    }
}
