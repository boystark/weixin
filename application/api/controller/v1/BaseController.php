<?php
/**
 * Created by PhpStorm.
 * User: kang
 * Date: 2017/9/4
 * Time: 2:02
 */

namespace app\api\controller\v1;

use think\Controller;
use \app\api\service\Token as TokenService;

class BaseController extends Controller
{


    protected function checkPrimaryScope(){
        TokenService::needPrimaryScope();
    }
    protected function checkExclusiveScope(){
        TokenService::needExclusiveScope();
    }
}