<?php
/**
 * Created by PhpStorm.
 * User: kang
 * Date: 2017/8/31
 * Time: 2:48
 */

namespace app\api\model;


use think\Model;

class BaseModel extends Model
{
    //供读取器调用！
    public function prefixImgUrl($value,$data){
        $url = $value;
        if($data['from'] == 1){
            $url = config('setting.img_prefix').$value;
        }
        return $url;
    }
}