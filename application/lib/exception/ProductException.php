<?php
/**
 * Created by PhpStorm.
 * User: kang
 * Date: 2017/9/1
 * Time: 13:59
 */

namespace app\lib\exception;


class ProductException extends BaseException
{
    //http状态码
    public $code = 404;
    //错误具体信息
    public $msg = '指定的商品不存在，请检查参数';
    //自定义的错误码
    public $errorCode = 20000;
}