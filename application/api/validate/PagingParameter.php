<?php
/**
 * Created by PhpStorm.
 * User: kang
 * Date: 2017/9/5
 * Time: 19:23
 */

namespace app\api\validate;


class PagingParameter extends BaseValidate
{
    protected $rule =[
        'page'=>'isPositiveInteger',
        'size'=>'isPositiveInteger'
    ];
    protected $message = [
        'page'=>'分页参数page必须是正整数',
        'size'=>'分页参数size必须是正整数'
    ];


}