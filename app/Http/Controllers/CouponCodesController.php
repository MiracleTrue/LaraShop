<?php

namespace App\Http\Controllers;

use App\Exceptions\CouponCodeUnavailableException;
use App\Models\CouponCode;
use Illuminate\Http\Request;

class CouponCodesController extends Controller
{

    /**
     * @param $code
     * @param Request $request
     * @return CouponCode|\Illuminate\Database\Eloquent\Model|null|object
     * @throws CouponCodeUnavailableException
     */
    public function show($code, Request $request)
    {
        if (!$record = CouponCode::where('code', $code)->first())
        {
            throw new CouponCodeUnavailableException('优惠券不存在');
        }

        $record->checkAvailable($request->user());

        return $record;
    }
}
