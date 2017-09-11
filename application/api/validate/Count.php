<?php
/**
 * Created by PhpStorm.
 * User: kang
 * Date: 2017/9/1
 * Time: 13:45
 */

namespace app\api\validate;


class Count extends BaseValidate
{
    protected $rule =[
      'count'=>'isPositiveInteger|between:1,30'
    ];
    protected $message = [
        'isPositiveInteger'=>'count必须是正整数',
        'between'=>'正整数必须在1到30之间'
    ];

}