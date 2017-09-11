<?php
/**
 * Created by PhpStorm.
 * User: kang
 * Date: 2017/9/1
 * Time: 15:02
 */

namespace app\lib\exception;


class CategoryException extends BaseException
{
    //http状态码
    public $code = 404;
    //错误具体信息
    public $msg = '请求的 分类 不存在';
    //自定义的错误码
    public $errorCode = 50000;

}