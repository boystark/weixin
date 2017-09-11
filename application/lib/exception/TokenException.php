<?php
/**
 * Created by PhpStorm.
 * User: kang
 * Date: 2017/9/3
 * Time: 3:35
 */

namespace app\lib\exception;


class TokenException extends BaseException
{
    //http状态码
    public $code = 401;
    //错误具体信息
    public $msg = 'Token已经过去或者无效';
    //自定义的错误码
    public $errorCode = 10001;

}