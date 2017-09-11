<?php
/**
 * Created by PhpStorm.
 * User: kang
 * Date: 2017/9/4
 * Time: 2:54
 */

namespace app\api\service;


use app\api\model\OrderProduct;
use app\api\model\Product as ProductModel;
use app\api\model\UserAddress;
use app\lib\exception\OrderException;
use app\lib\exception\UserException;
use think\Db;
use think\Exception;

class Order
{
    /**
     * 客户端传递过来的products
     * @var
     */
    protected $oProducts;
    /**
     * 真实的商品信息
     * @var
     */
    protected $products;
    protected $uid;

    /**
     * s数据储存
     * @param $uid
     * @param $oProducts
     */
    public function place($uid,$oProducts){
        //把$oProducts和$products  作为对比
        // 查询数据库 $products
        $this->uid = $uid;
        $this->oProducts=$oProducts;
        $this->products=$this->getProductsByOrder($oProducts);

        $status = $this->getOrderStatus();
        if(!$status['pass']){
            $status['order_id'] = -1;
            return $status;
        }
        //创建订单快照
        $orderSnap = $this->snapOrder($status);
        //生成订单
        $order = $this->createOrder($orderSnap);
        $order['pass'] = true;

        return $order;
    }

    /**
     * 写入订单信息
     * @param $snap
     * @return array
     * @throws Exception
     */
    private function createOrder($snap){
        Db::startTrans();
        try{
            $orderNo = self::makeOrderNo();
            $order = new \app\api\model\Order();
            $order->user_id = $this->uid;
            $order->order_no = $orderNo;
            $order->total_price = $snap['orderPrice'];
            $order->total_count = $snap['totalCount'];
            $order->snap_img = $snap['snapImg'];
            $order->snap_name = $snap['snapName'];
            $order->snap_address = $snap['snapAddress'];
            $order->snap_items = json_encode($snap['pStatus']);
            $order->save();

            $orderId = $order->id;
            $createTime = $order->create_time;
            foreach ($this->oProducts as &$p){
                $p['order_id'] = $orderId;
            }

            $orderProduct = new OrderProduct();
            $orderProduct->saveAll($this->oProducts );
        Db::commit();
            return [
                'order_no' => $orderNo,
                'order_id' => $orderId,
                'create_time' => $createTime,
            ];
        }catch (Exception $e){
            Db::rollback();
            throw $e;
        }


    }

    /**
     * 生成订单号
     * @return string
     */
    public static function makeOrderNo(){
        $yCode = array('A','B','C','D','E','F','G','H','I','J','K','L');
        $orderSn =
            $yCode[intval(date('Y')) - 2017].strtoupper(dechex(date('m'))).date(
            'd').substr(time(),-5).substr(microtime(),2,5).sprintf(
            '%02d',rand(0,99));
        return $orderSn;
    }

    /**
     * 生成订单快照
     */
    private function snapOrder($status){
        $snap = [
          'orderPrice'=>0,
          'totalCount'=>0,
          'pStatus'=>[],
          'snapAddress' => null,
          'snapName'=>'',
          'snapImg'=>''
        ];

        $snap['orderPrice'] = $status['orderPrice'];
        $snap['totalCount'] = $status['totalCount'];
        $snap['pStatus'] = $status['pStatusArray'];
        $snap['snapAddress'] = json_encode($this->getUserAddress());
        $snap['snapName'] = $this->products[0]['name'];
        $snap['snapImg'] = $this->products[0]['main_img_url'];
        if(count( $this->products) > 1){
            $snap['snapName'] .='等';
        }
        return $snap;
    }

    /**
     * 获取用户的地址信息
     */
    private function getUserAddress(){
        $userAddress = UserAddress::where('user_id','=',$this->uid)->find();
        if(!$userAddress){
            throw new UserException([
                'msg'=>'用户收货地址不存在，下单失败',
                'errorCode'=>60001,
            ]);
        }
        return $userAddress->toArray();
    }

    /**
     *这个可以用于支付 完成支付核对数量
     */
    public function checkOrderStock($orderID){
        $oProducts = OrderProduct::where('order_id','=',$orderID)->select();
        $this->oProducts = $oProducts;
        $products = self::getProductsByOrder($oProducts);
        $this->products = $products;
        $status = $this->getOrderStatus();
        return $status;
    }
    /**
     * 查找所有商品是否存在
     * 对比两个的参数$oProducts $products
     */
    private function getOrderStatus() {
        $status = [
            'pass'=>true,
            'orderPrice'=>0,
            'totalCount'=>0,
            'pStatusArray'=>[]
        ];
        foreach ($this->oProducts as $oProduct){
            $pStatus = $this->getProductStatus($oProduct['product_id'],$oProduct['count'],$this->products);
            if(!$pStatus['haveStock']){
                $status['pass'] = false;
            }
            $status['orderPrice'] += $pStatus['totalPrice'];
            $status['totalCount'] += $pStatus['counts'];
            array_push($status['pStatusArray'],$pStatus);
        }
        return $status;
    }

    /**
     * 查找某个商品是否存在
     * @param $oProduct
     */
    private function getProductStatus($oPID,$oCount,$products){
        $pIndex = -1;
        $pStatus =[
            'id'=>null,
            'haveStock'=>false,
            'main_img_url'=>'',
            'counts'=>0,
            'name'=>'',
            'price'=>'',
            'totalPrice'=>0
        ];
        for($i=0;$i<count($products);$i++){
            if($oPID == $products[$i]['id']){
                $pIndex = $i;
            }
        }
        //客户端传来的id有可能根本不存在
        if($pIndex == -1){
            throw new OrderException([
               'msg'=>'id为'.$oPID.'商品不存在，创建订单失败'
            ]);
        }
        else{
            $product = $products[$pIndex];
            $pStatus['id'] = $product['id'];
            $pStatus['name'] = $product['name'];
            $pStatus['counts'] = $oCount;
            $pStatus['totalPrice'] = $product['price'] * $oCount;
            $pStatus['price'] = $product['price'];
            $pStatus['main_img_url'] = $product['main_img_url'];

            if($product['stock'] - $oCount >= 0){
                $pStatus['haveStock'] = true;
            }
        }
        return $pStatus;
    }

    /**
     * 查找真实的商品信息
     */
    private function getProductsByOrder($oProducts){
        $oPIDs=[];
        //避免数据库循环查找
        foreach ($oProducts as $items){
            array_push($oPIDs,$items['product_id']);
        }

        $products = ProductModel::all($oPIDs);
        $data =[];
        foreach ($products as $value){
            $res = $value ->visible(['id', 'price', 'stock', 'name', 'main_img_url'])->toArray();
            array_push($data,$res);
        }
        return $data;
    }
}