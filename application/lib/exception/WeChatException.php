<?php
/**
 * Created by PhpStorm.
 * User: kang
 * Date: 2017/9/3
 * Time: 1:58
 */

namespace app\lib\exception;


class WeChatException extends BaseException
{
    //http状态码
    public $code = 400;
    //错误具体信息
    public $msg = '微信借口错误';
    //自定义的错误码
    public $errorCode = 999;

}