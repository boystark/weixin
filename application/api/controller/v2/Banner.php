<?php
/**
 * Created by PhpStorm.
 * User: kang
 * Date: 2017/8/30
 * Time: 22:28
 */

namespace app\api\controller\v2;
use think\Controller;

class Banner extends Controller
{
    /**
     * 获取指定id的banner信息
     * @param $id banner 的id号
     * @http GET
     */
    public function getBanner($id){
        return 'this is v2  version';
        }
}