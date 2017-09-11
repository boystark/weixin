<?php
/**
 * Created by PhpStorm.
 * User: kang
 * Date: 2017/9/3
 * Time: 3:08
 */

namespace app\api\service;

use think\Cache;
use think\Exception;
use think\Request;
use app\lib\enum\ScopeEnum;
use app\lib\exception\ForbiddenException;
use app\lib\exception\TokenException;

class Token
{
    public static function generateToken(){
        //32个字符组成一组随机字符串
        $randChars = getRandChar(32);
        //md5加密
        $timestamp = $_SERVER['REQUEST_TIME'];
        //salt 盐
        $salt = config('secure.token_salt');
        return md5($randChars.$timestamp.$salt);
    }

    public static function getCurrentUid(){
        //token
        $uid = self::getCurrentTokenVar('uid');
        return $uid;
    }

    public static function getCurrentTokenVar($key){
        $token = Request::instance()->header('token');
        $vars = Cache::get($token);
        if(!$vars){
            throw new TokenException();
        }else{
           if(!is_array($vars)){
               $vars = json_decode($vars,true);
           }
            if(array_key_exists($key,$vars)){
               return $vars[$key];
            }else{
                throw new Exception('尝试获取Token的变量并不存在');
            }
        }
    }

    /**
     * 用户和管理员都可以访问
     * @return bool
     * @throws ForbiddenException
     * @throws TokenException
     */
    public static function needPrimaryScope(){
        $scope = self::getCurrentTokenVar('scope');
        if($scope)
        {
            if($scope >= ScopeEnum::User){
                return true;
            }else{
                throw new ForbiddenException();
            }
        }else{
            throw new TokenException();
        }
    }

    /**
     * 只有用户才能访问
     * @return bool
     * @throws ForbiddenException
     * @throws TokenException
     */
    public static function needExclusiveScope(){
        $scope = self::getCurrentTokenVar('scope');
        if($scope)
        {
            if($scope == ScopeEnum::User){
                return true;
            }else{
                throw new ForbiddenException();
            }
        }else{
            throw new TokenException();
        }
    }

    /**
     *
     */
    public static function isValidOperate($checkUID){
        if(!$checkUID){
            throw new Exception('检查UID时候，必须床底一个被检查的uid');
        }
        $currentOperateUID = self::getCurrentUid();

        if($currentOperateUID == $checkUID){
            return true;
        }
        return false;
    }

    /**
     * 验证token是否有效
     */
    public static function verifyToken($token){
        $exist = Cache::get($token);
        if($exist){
            return true;
        }else{
            return false;
        }
    }


}