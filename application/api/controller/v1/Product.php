<?php
/**
 * Created by PhpStorm.
 * User: kang
 * Date: 2017/9/1
 * Time: 13:43
 */

namespace app\api\controller\v1;


use app\api\validate\Count;
use app\api\validate\IDMustBePositiveInt;
use app\lib\exception\ProductException;
use app\api\model\Product as ProductModel;


class Product extends BaseController
{

    public function getRecent($count = 15){
        (new Count())->goCheck();
        $result = ProductModel::getMostRecent($count);
        if(!$result){
            throw new ProductException();
        }
        return json($result,200);
    }

    public function  getAllInCategory($id){
        (new IDMustBePositiveInt())->goCheck();
        $result = ProductModel::getProductsByCategoryID($id);

        if(!$result){
            throw new ProductException();
        }
        return json($result,200);
    }

    public function getOne($id)
    {
        (new IDMustBePositiveInt())->goCheck();
        $product = ProductModel::getProductDetail($id);
        if(!$product){
            throw new ProductException();
        }
        return json($product,200);

    }

    public function deleteOne($id){

    }
}