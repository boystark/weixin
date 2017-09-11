<?php
/**
 * Created by PhpStorm.
 * User: kang
 * Date: 2017/9/5
 * Time: 1:15
 */

namespace app\api\controller\v1;


use app\api\service\WxNotify as WxNotifyService;
use app\api\validate\IDMustBePositiveInt;
use \app\api\service\Pay as PayService;
class Pay extends BaseController
{
    /**
     * 请求预定义叮当
     */
    protected $beforeActionList = [
        'checkExclusiveScope'=>['only'=>'getPreOrder']
    ];

    public function getPreOrder($id = ''){
        (new IDMustBePositiveInt())->goCheck();
        $pay = new PayService($id);
        return $pay->pay();
    }
    public function receiveNotify(){
        //微信回调的频率为15//15//30//180//1800//1800//1800//1800//3600（S）
        //还是要检测库存量，（超卖），可能性小
        //更新订单状态
        //减少库存
        //向微信返回结果（成功或是失败）
        //post; xml; 不能携带参数
        $notify = new WxNotifyService();
        $notify->Handle();
    }

}





