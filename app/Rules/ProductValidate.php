<?php

namespace App\Rules;

use Illuminate\Support\Facades\Validator;

class ProductValidate
{
    public static function validate(array $data)
    {
        $validator = Validator::make($data, [
            'cat_id' => 'required',
            'cat_list' => 'required',
            'maso' => 'required|min:3',
            'macn' => 'required|min:3',
            'picture' => 'required',
            'price' => 'required',
            'price_old' => 'required',
            'stock' => 'required',
            'url' => 'required',
            'title' => 'required',
            'description' => 'required',
            'friendly_url' => 'required',
            'friendly_title' => 'required',
            'metakey' => 'required',
            'metadesc' => 'required',
        ]);
        return $validator;
    }
}