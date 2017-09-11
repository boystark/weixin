<?php
/**
 * Created by PhpStorm.
 * User: kang
 * Date: 2017/9/1
 * Time: 4:43
 */

namespace app\lib\exception;


class ThemeException extends BaseException
{

    //http状态码
    public $code = 404;
    //错误具体信息
    public $msg = '请求的 Theme 不存在';
    //自定义的错误码
    public $errorCode = 30000;

}