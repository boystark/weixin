<?php
/**
 * Created by PhpStorm.
 * User: kang
 * Date: 2017/9/1
 * Time: 22:16
 */

namespace app\api\validate;


class TokenGet extends BaseValidate
{
    protected $rule = [
        'code'=>'require|isNotEmpty'
    ];
    protected $message = [
        'code'=>'没有code，不能获取token'
    ];
}