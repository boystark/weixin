<?php

namespace app\api\model;


class Image extends BaseModel
{
    protected $hidden =['id','delete_time','update_time','from'];

    //读取器 get + 名称 + attr  驼峰命名
    public function getUrlAttr($value,$data){
        return $this->prefixImgUrl($value,$data);
    }

}
