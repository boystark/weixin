<?php
/**
 * Created by PhpStorm.
 * User: kang
 * Date: 2017/9/3
 * Time: 18:42
 */

namespace app\lib\exception;


class ForbiddenException extends BaseException
{
    //http状态码
    public $code = 403;
    //错误具体信息
    public $msg = '权限不够，禁止操作';
    //自定义的错误码
    public $errorCode = 10001;
}