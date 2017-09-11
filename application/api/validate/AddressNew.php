<?php
/**
 * Created by PhpStorm.
 * User: kang
 * Date: 2017/9/3
 * Time: 13:29
 */

namespace app\api\validate;


class AddressNew extends BaseValidate
{
    protected $rule =[
        'name'=>'require|isNotEmpty',
        'mobile'=>'require|isMobile',
        'province'=>'require|isNotEmpty',
        'city'=>'require|isNotEmpty',
        'country'=>'require|isNotEmpty',
        'detail'=>'require|isNotEmpty',
    ];
}