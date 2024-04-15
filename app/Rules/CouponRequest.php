<?php

namespace App\Rules;

use Illuminate\Support\Facades\Validator;

class CouponRequest
{
    public static function validate(array $data)
    {
        $validator = Validator::make($data, [
            'TenCoupon' => 'required',
            'MaPhatHanh' => 'required',
            'StartCouponDate' => 'required',
            'EndCouponDate' => 'required',
        ]);
        return $validator;
    }
}