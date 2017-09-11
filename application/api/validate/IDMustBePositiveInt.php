<?php
/**
 * Created by PhpStorm.
 * User: kang
 * Date: 2017/8/31
 * Time: 1:29
 */

namespace app\api\validate;

use app\api\validate\BaseValidate;
class IDMustBePositiveInt extends BaseValidate
{
    protected $rule =[
        'id'=>'require|isPositiveInteger',
    ];
    protected $message = [
        'isPositiveInteger'=>'ID必须是正整数'
    ];
}