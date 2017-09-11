<?php
/**
 * Created by PhpStorm.
 * User: kang
 * Date: 2017/9/5
 * Time: 1:33
 */

namespace app\api\service;


use app\lib\enum\OrderStatusEnum;
use app\lib\exception\OrderException;
use app\lib\exception\TokenException;
use think\Exception;
use app\api\service\Order as OrderService;
use app\api\model\Order as OrderModel;
use think\Loader;
use think\Log;

//读取extend下面的微信支付类 extend/WxPay/WxPay.Api.php
Loader::import('WxPay.WxPay',EXTEND_PATH,'.Api.php');

class Pay
{
    private $orderID;
    private $orderNO;

    public function __construct($orderID)
    {
        if(!$orderID){
            throw new Exception('订单号不能为空');
        }
        $this->orderID = $orderID;
    }

    public function pay(){
        //检测订单是否可用
        $this->checkOrderValid();
        //进行库存量检测
        $orderService = new OrderService();
        $status = $orderService->checkOrderStock($this->orderID);
        if(!$status['pass']){
            return $status;
        }
        return $this->makeWxPreOrder($status['totalPrice']);
    }
    //微信支付
    private function makeWxPreOrder($totalPrice){
        //openid
        $openid = Token::getCurrentTokenVar('openid');
        if(!$openid){
            throw new Exception('');
        }
        //反斜杠很重要
        $wxOrderData = new \WxPayUnifiedOrder();
        $wxOrderData->SetOut_trade_no($this->orderNO);
        $wxOrderData->SetTrade_type('JSAPI');
        $wxOrderData->SetTotal_fee($totalPrice*100);
        $wxOrderData->SetBody('嘻哈哈');
        $wxOrderData->SetOpenid($openid);
        //import 接口地址
        $wxOrderData->SetNotify_url(config('secure.pay_back_url'));
        return $this->getPaySignature($wxOrderData);
    }

    private function getPaySignature($wxOrderData){
        $wxOrder=  \WxPayApi::unifiedOrder($wxOrderData);
        // 失败时不会返回result_code
        if($wxOrder['return_code'] != 'SUCCESS' || $wxOrder['result_code'] !='SUCCESS'){
            Log::record($wxOrder,'error');
            Log::record('获取预支付订单失败','error');
           throw new Exception('获取预支付订单失败');
        }
        $this->recordPreOrder($wxOrder);
        $signature = $this->sign($wxOrder);
        return $signature;

    }

    //签名
    private function sign($wxOrder){
        $jsApiPayData = new \WxPayJsApiPay();
        $jsApiPayData->SetAppid(config('wx.app_id'));
        $jsApiPayData->SetTimeStamp((string)time());
        $randStr = md5(time().mt_rand(0,999));
        $jsApiPayData->SetNonceStr($randStr);

        $jsApiPayData->SetPackage('prepay_id='.$wxOrder['prepay_id']);
        $jsApiPayData->SetSignType('md5');

        $sign = $jsApiPayData->MakeSign();

        $rawValues = $jsApiPayData->GetValues();

        $rawValues['paySign']=$sign;

        //删除appID
        unset($rawValues['appId']);

        return $rawValues;
    }

    /**
     * 保存prepay_id//更新
     * @param $wxOrder
     */
    private function recordPreOrder($wxOrder){
        OrderModel::where('id','=',$this->orderID)->update([
            'prepay_id'=>$wxOrder['prepay_id']
        ]);
    }

    /**
     * 检测订单
     * @throws OrderException
     * @throws TokenException
     */
    private function checkOrderValid(){
        $order = OrderModel::where('id','=',$this->orderID)->find();
        //订单号可能根本不存在
        if(!$order){
            throw new OrderException();
        }
        //订单存在，当是与用户不匹配
        if(!Token::isValidOperate($order->user_id)){
            throw new TokenException([
                'msg'=>'订单与用户不匹配',
                'errorCode'=>10003
            ]);
        };
        //订单没有被支付
        if($order->status != OrderStatusEnum::UNPAID){
            throw new OrderException([
                'msg'=>'订单已经支付过了',
                'errorCode'=> 8003,
                'code'=>400
            ]);
        }

       $this->orderNO = $order->order_no;
    }

}