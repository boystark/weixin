<?php
/**
 * Created by PhpStorm.
 * User: kang
 * Date: 2017/9/3
 * Time: 20:21
 */

namespace app\api\controller\v1;

use app\api\validate\IDMustBePositiveInt;
use app\api\validate\OrderPlace;
use \app\api\service\Token as TokenService;
use \app\api\service\Order as OrderService;
use \app\api\model\Order as OrderModel;
use app\api\validate\PagingParameter;
use app\lib\exception\OrderException;

class Order extends BaseController
{
//用户在选择商品后，向API提交它所选择商品的相关商品的信息
//API收到信息后，检查商品的库存量
//有库存，把订单数据存入数据库，返回客户端消息，让客服端可以支付消息
//调用支付端接口，进行支付
//还是需要检测库存量，有存量就可以调用微信的支付接口支付
//小程序根据服务器返回结果拉起微信支付
//微信返回一个支付(异步调用，返回两个结果一个给服务器，一个给微信小程序)
//成功也需要进行库存检查
//如果用户支付成功就可以减去库存量
    protected $beforeActionList = [
        'checkExclusiveScope'=>['only'=>'placeOrder'],
        'checkPrimaryScope'=>['only'=>'getSummaryByUser,getDetail']
    ];

    public function placeOrder(){
        //商品id,商品数量
        (new OrderPlace())->goCheck();
        $products = input('post.products/a');

        $uid = TokenService::getCurrentUid();
        $order = new OrderService();
        $status = $order->place($uid,$products);
        return json($status,200);
    }

    public function getSummaryByUser($page = 1,$size = 15){
        (new PagingParameter())->goCheck();
        $uid = \app\api\service\Token::getCurrentUid();
        $pageOrders = OrderModel::getSummaryByUser($uid,$page,$size);
        if($pageOrders->isEmpty()){
            return json([
                'data'=>[],
                'current_page'=> $page
            ]);
        }

        $data = $pageOrders->toArray();
        foreach ($data['data'] as &$value){
            unset($value['snap_items'],$value['snap_address'],$value['prepay_id']);
        }
        return json([
            'data'=> $data,
            'current_page'=> $pageOrders->currentPage()
        ]);
    }

    public function getDetail($id){
        (new IDMustBePositiveInt())->goCheck();
        $orderDetail = OrderModel::get($id);
        if(!$orderDetail){
            throw new OrderException();
        }

        $data = $orderDetail->toArray();

        unset($data['prepay_id']);
        return json($data);
    }


}
