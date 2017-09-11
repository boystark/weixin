<?php
/**
 * Created by PhpStorm.
 * User: kang
 * Date: 2017/8/31
 * Time: 1:58
 */

namespace app\api\validate;

use app\lib\exception\ParameterException;
use think\Request;
use think\Validate;

class BaseValidate extends Validate
{
    protected function isPositiveInteger($value,$rule='',
                                         $data='',$field=''){
        $value = $value + 0;
        if(is_numeric($value) && is_int($value) && $value > 0){
            return true;
        }else{
            return false;
        }
    }

    protected function isNotEmpty($value,$rule='',
                                         $data='',$field=''){
        if(empty($value)){
            return false;
        }else{
            return true;
        }
    }
    protected function isMobile($value,$rule='',
                                         $data='',$field=''){
        $rule = '^1(3|4|5|7|8)[0-9]\d{8}$^';

        $result = preg_match($rule,$value);

        if($result){
            return true;
        }else{
            return false;
        }
    }


    public function goCheck(){
        //获取查询的参数
        $request = Request::instance();
        $param = $request->param();

        $result = $this->batch()->check($param);
        if(!$result){
            $e = new ParameterException([
                'msg'=> $this->error,
                'code'=> 400,
                'errorCode'=>10002
            ]);
            throw $e;
        }else{
            return true;
        }
    }

    public function getDataByRule($arrays){
        //不允许用户传递id
        if(array_key_exists('user_id',$arrays) |
            array_key_exists('uid',$arrays)){
            throw new ParameterException([
               'msg'=>'参数中包含非法的参数名user_id或者uid'
            ]);
        }
        $newArray =[];
        foreach ($this->rule as $key=>$value){
            $newArray[$key] = $arrays[$key];
        }
        return $newArray;
    }
}