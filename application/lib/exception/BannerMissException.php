<?php
/**
 * Created by PhpStorm.
 * User: kang
 * Date: 2017/8/31
 * Time: 13:46
 */

namespace app\lib\exception;


class BannerMissException extends BaseException
{
    //http状态码
    public $code = 404;
    //错误具体信息
    public $msg = '请求的 Banner 不存在';
    //自定义的错误码
    public $errorCode = 40000;

}