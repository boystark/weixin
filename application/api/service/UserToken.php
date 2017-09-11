<?php
/**
 * Created by PhpStorm.
 * User: kang
 * Date: 2017/9/1
 * Time: 22:25
 */

namespace app\api\service;


use app\api\model\User;
use app\lib\enum\ScopeEnum;
use app\lib\exception\TokenException;
use app\lib\exception\WeChatException;
use think\Exception;

class UserToken extends Token
{
    protected $code;
    protected $wxAppid;
    protected $wxAppSecret;
    protected $wxLoginUrl;

    public function __construct($code)
    {
        $this->code = $code;
        $this->wxAppid = config('wx.app_id');
        $this->wxAppSecret = config('wx.app_secret');
        $this->wxLoginUrl = sprintf(config('wx.login_url'), $this->wxAppid, $this->wxAppSecret, $this->code );
    }

    public function get(){
        $result = curl_get($this->wxLoginUrl);
        $wxResult = json_decode($result,true);
        if(empty($wxResult)){
            throw new Exception('获取session_key及openID异常');
        }else{
            $loginFail = array_key_exists('errcode',$wxResult);
            if($loginFail){
                $this->processLoginError($wxResult);
            }else{
                return $this->grantToken($wxResult);
            }
        }

        return $wxResult;
    }

    private function processLoginError($wxResult){
        throw new WeChatException([
            'msg'=>$wxResult['errmsg'],
            'errorCode'=>$wxResult['errcode']
        ]);
    }

    //获取token
    private function grantToken($wxResult){
        //拿到openid
        $opid = $wxResult['openid'];
        //数据库里看看是否存在
        $user = User::getByOpenID($opid);
        if($user){
            $uid = $user->id;
        }else{
            $uid = $this ->newUser($opid);
        }
        //生成令牌，准备数据缓存，写入缓存
        //key：令牌
        //value：wxResult,uid,scope

        $cacheValue = $this->prepareCachedValue($wxResult,$uid);
        $token = $this ->saveToCache($cacheValue);
        return $token;
    }

    /**
     * 创建一个用户
     * @param $openid 用户的openid
     * @return mixed
     */
    private function newUser($openid){
        $user = User::create([
           'openid'=> $openid
        ]);
        return $user->id;
    }

    //准备缓存数据
    private function prepareCachedValue($wxResult,$uid){
        $cacheValue = $wxResult;
        $cacheValue['uid'] = $uid;
        $cacheValue['scope'] = ScopeEnum::User;
        return $cacheValue;
    }
    //储存缓存数据
    private function saveToCache($cacheValue){
        $key = self::generateToken();
        $value = json_encode($cacheValue);
        $expire_in = config('setting.token_expire_in');
        //TP5自带缓存
        $request = cache($key,$value,$expire_in);

        if(!$request){
            throw new TokenException([
                'msg'=>'服务器缓存异常',
                'errorCode'=>10005,
            ]);
        }
        return $key;
    }



}