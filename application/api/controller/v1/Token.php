<?php
/**
 * Created by PhpStorm.
 * User: kang
 * Date: 2017/9/1
 * Time: 22:14
 */

namespace app\api\controller\v1;

use app\api\service\UserToken;
use app\api\validate\TokenGet;
use app\lib\exception\ParameterException;
use app\api\service\Token as TokenService;

class Token extends BaseController
{
    public function getToken($code=''){
        ( new TokenGet())->goCheck();
        $ut = new UserToken($code);
        $token = $ut->get();
        return json([
            'token'=>$token
        ],200);
    }
    public function verifyToken($token=''){
       if(!$token){
           throw new ParameterException([
               'Token不允许为空'
           ]);
       }
       $valid = TokenService::verifyToken($token);

       return json(['isValid'=>$valid],200);

    }

}