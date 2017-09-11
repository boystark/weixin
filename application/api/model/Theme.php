<?php

namespace app\api\model;



class Theme extends BaseModel
{
    protected $hidden =['delete_time','update_time','head_img_id','topic_img_id'];
    public function topicImg(){
        return $this->belongsTo('Image','topic_img_id','id');
        }
    public function headImg(){
        return $this->belongsTo('Image','head_img_id','id');
    }
    public function  products(){
        return $this->belongsToMany('Product','theme_product','product_id','theme_id');
    }

    public static function getThemeByIDs($ids)
    {
        $ids = explode(',',$ids);
        $theme = self::with(['topicImg','headImg'])->select($ids);
        return $theme;
    }
    public static function getThemeByID($id)
    {
        $theme = self::with(['products','topicImg','headImg'])->find($id);
        return $theme;
    }
}
