<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006~2016 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------

use think\Route;

// 设置name变量规则（采用正则定义）
// 支持批量添加
//Route::pattern([
//    'name'  =>  '\w+',
//    'id'    =>  '\d+',
//]);

Route::get('/','index/index/index');

//Route::rule(‘路由表达式’,‘路由地址’,‘请求类型’,‘路由参数（数组）’,‘变量规则（数组）’);
//获取banner
Route::get('api/:version/banner/:id','api/:version.Banner/getBanner',[],['id'=>'\d+']);

//获取某个主题的详细信息
Route::get('api/:version/theme/:id','api/:version.Theme/getComplexOne',[],['id'=>'\d+']);

//获取主题
Route::get('api/:version/theme','api/:version.Theme/getSimpleList');


//分组路由 product
Route::group('api/:version/product',function (){
    //获取最新的产品
    Route::get('/recent','api/:version.Product/getRecent');
//获取某个分类的各个产品
    Route::get('/by_category','api/:version.Product/getAllInCategory');
//获取某个
    Route::get('/:id','api/:version.Product/getOne',[],['id'=>'\d+']);

});

//获取所有的分类
Route::get('api/:version/category/all','api/:version.Category/getAllCategories');

//获取用户token
Route::post('api/:version/token/user','api/:version.Token/getToken');
//验证用户token
Route::post('api/:version/token/verify','api/:version.Token/verifyToken');

//获取用户address
Route::get('api/:version/address','api/:version.Address/getUserAddress');

//创建或跟新用户地址
Route::post('api/:version/address','api/:version.Address/createOrUpdateAddress');


//支付相关
//下单
Route::post('api/:version/order','api/:version.Order/placeOrder');
//获取某个订单的详情
Route::get('api/:version/order/:id','api/:version.Order/getDetail',[],['id'=>'\d+']);
//获取历史订单
Route::get('api/:version/order/by_user','api/:version.Order/getSummaryByUser');

//支付
Route::post('api/:version/pay/pre_order','api/:version.Pay/getPreOrder');
//微信回调Api
Route::post('api/:version/pay/notify','api/:version.Pay/receiveNotify');




















