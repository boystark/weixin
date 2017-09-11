<?php
/**
 * Created by PhpStorm.
 * User: kang
 * Date: 2017/9/3
 * Time: 13:26
 */

namespace app\api\controller\v1;

use app\api\model\User;
use app\api\model\UserAddress;
use app\api\validate\AddressNew;
use \app\api\service\Token as TokenService;
use app\lib\exception\SuccessMessage;
use app\lib\exception\UserException;

class Address extends BaseController
{
    //前置
    protected $beforeActionList = [
        'checkPrimaryScope'=>['only'=>'createOrUpdateAddress,getUserAddress']
    ];

    public function createOrUpdateAddress(){

        $validate = new AddressNew();
        $validate->goCheck();
        //根据token来获取uid
        //根据uid来查找用户数据，判读是否存在
        $uid = TokenService::getCurrentUid();
        $user = User::get($uid);
        if(!$user){
            throw new UserException();
        }
        //获取用户从客户端传来的地址信息
        $data = $validate->getDataByRule(input('post.')) ;
        //根据用户地址信息是否存在，从而判断是添加还是更新
        //这里运用模型的关联模型
        $userAddress = $user->address;
        if(!$userAddress){
            $user->address()->save($data);
        }else{
            $user->address->save($data);
        }
//        return $user;
        return json(new SuccessMessage(),201);
    }

    public function getUserAddress(){
        $uid = TokenService::getCurrentUid();
        $userAddress = UserAddress::where('user_id',$uid)->find();

        if(!$userAddress){
            Throw new UserException([
                'msg'=>'用户地址不存在',
                'errorCode'=>60001
            ]);

        }
        return json($userAddress);
    }
}