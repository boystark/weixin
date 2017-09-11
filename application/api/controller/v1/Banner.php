<?php
/**
 * Created by PhpStorm.
 * User: kang
 * Date: 2017/8/30
 * Time: 22:28
 */

namespace app\api\controller\v1;

use app\lib\exception\BannerMissException;
use app\api\validate\IDMustBePositiveInt;
use \app\api\model\Banner as BannerModel;

class Banner extends BaseController
{
    /**
     * 获取指定id的banner信息
     * @param $id banner 的id号
     * @http GET
     */
    public function getBanner($id){
        (new IDMustBePositiveInt())->goCheck();
         $banner = BannerModel::getBannerByID($id);
        if(!$banner){
            throw new BannerMissException();
        }
         return json($banner,200);
        }
}