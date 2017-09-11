<?php
/**
 * Created by PhpStorm.
 * User: kang
 * Date: 2017/9/4
 * Time: 2:26
 */

namespace app\api\validate;


use app\lib\exception\ParameterException;

class OrderPlace extends BaseValidate
{
    protected $rule = [
      'products'=>'require|checkProducts'
    ];

    protected function checkProducts($value,$rule='',
                                     $data='',$field=''){

        if(!is_array($value)){
            throw new ParameterException([
                'msg'=>'商品参数应该是数组'
            ]);
        }
        if(empty($value)){
            throw new ParameterException([
                'msg'=>'商品列表不能为空'
            ]);
        }
        foreach ($value as $v){
            $this->checkProduct($v);
        }
        //其他情况都会抛出异常
        return true;
    }

    protected $singleRule = [
      'product_id'=>  'require|isPositiveInteger',
      'count'=>'require|isPositiveInteger'
    ];

    protected function checkProduct($value){
        $validate = new BaseValidate($this->singleRule);
        $result = $validate->check($value);
        if(!$result){
            throw new ParameterException([
                'msg'=>'产品和数量参数出现错误'
            ]);
        }
    }
}