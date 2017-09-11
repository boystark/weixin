<?php
/**
 * Created by PhpStorm.
 * User: kang
 * Date: 2017/9/3
 * Time: 16:09
 */

namespace app\lib\exception;


class SuccessMessage extends BaseException
{
    //http状态码
    public $code = 201;
    //错误具体信息
    public $msg = 'ok';
    //自定义的错误码
    public $errorCode = 0;

}