<?php

namespace app\api\model;


class Order extends BaseModel
{
    protected $hidden =['user_id','delete_time','update_time'];
    protected $autoWriteTimestamp = true;


    public function getSnapItemsAttr($name)
    {
        if(empty($name)){
            return null;
        }

        return json_decode($name);
    }
    public function getSnapAddressAttr($name)
    {
        if(empty($name)){
            return null;
        }

        return json_decode($name);
    }

    //分页
    public static function getSummaryByUser($uid,$page=1,$size=15){
        $pagingData = self::where('user_id','=',$uid)
            ->order('create_time desc')
            ->paginate($size,true,['page'=>$page]);
        return $pagingData;
    }

}
