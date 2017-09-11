<?php
/**
 * Created by PhpStorm.
 * User: kang
 * Date: 2017/8/31
 * Time: 2:46
 */

namespace app\api\model;

class Banner extends BaseModel
{
    protected $hidden =['delete_time','update_time'];
    public function items(){
        return $this->hasMany('BannerItem','banner_id','id');
    }


    public static function getBannerById($id)
    {
        $banner = self::with(['items','items.img'])->find($id);
//        $banner->hidden(['update_time']);
//        $banner->visible(['id']);

        return $banner;
    }
}