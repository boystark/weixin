<?php
/**
 * Created by PhpStorm.
 * User: kang
 * Date: 2017/8/31
 * Time: 13:55
 */

namespace app\lib\exception;


use think\exception\Handle;
use think\Log;
use think\Request;

class ExceptionHandler extends Handle
{
    private $code;
    private $msg;
    private $errorCode;

    public function render(\Exception $e)
    {
        if($e instanceof BaseException){
            //如果是自定义异常
            $this->code = $e->code;
            $this->msg =$e->msg;
            $this->errorCode = $e->errorCode;
        }else{
            if(config('app_debug')){
                return parent::render($e);
            }else{
                $this->code = 500;
                $this->msg ='内部错误，不告诉你';
                $this->errorCode = 999;
                $this->recordError($e);
            }

        }
        $request =  Request::instance();
        $result = [
            'msg'=>$this->msg,
            'error_code'=>$this->errorCode,
            'request_url'=>$request->url()
        ];

        return json($result,$this->code);
    }

    private function recordError(\Exception $e){
        Log::init([
            'type'=>'File',
            'path'=>LOG_PATH,
            'level'=>['error']
        ]);
        Log::record($e->getMessage(),'error');
    }
}