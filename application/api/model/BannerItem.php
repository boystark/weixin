<?php

namespace app\api\model;

use \app\api\model\BaseModel;

class BannerItem extends BaseModel
{
    protected $hidden =['delete_time','update_time','id','img_id','banner_id'];
    public function img(){
        return $this->belongsTo('Image','img_id','id');
    }

}
