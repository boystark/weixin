<?php
/**
 * Created by PhpStorm.
 * User: kang
 * Date: 2017/9/3
 * Time: 15:23
 */

namespace app\lib\exception;


class UserException extends BaseException
{
    //http状态码
    public $code = 404;
    //错误具体信息
    public $msg = '用户不存在';
    //自定义的错误码
    public $errorCode = 60000;
}