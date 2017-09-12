<?php
/**
 * Created by PhpStorm.
 * User: kang
 * Date: 2017/9/12
 * Time: 10:26
 */

namespace app\index\controller;


use think\Controller;

class Index extends Controller
{
    public function index(){
        return view('index');
    }
}