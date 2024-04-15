<?php

namespace App\Rules;

use Illuminate\Support\Facades\Validator;

class CouponDesUsingRequest
{
    public static function validate(array $data)
    {
        $validator = Validator::make($data, [
            'IDuser' => 'required',
            'idCouponDes' => 'required',
            'DateUsingCode' => 'required',
            'IDOrderCode' => 'required',
            'MaCouponUSer' => 'required',
        ]);
        return $validator;
    }
}