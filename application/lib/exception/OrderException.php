<?php
/**
 * Created by PhpStorm.
 * User: kang
 * Date: 2017/9/4
 * Time: 4:04
 */

namespace app\lib\exception;


class OrderException extends BaseException
{
    //http状态码
    public $code = 404;
    //错误具体信息
    public $msg = '订单不合法';
    //自定义的错误码
    public $errorCode = 80000;

}