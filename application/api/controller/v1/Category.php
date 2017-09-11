<?php
/**
 * Created by PhpStorm.
 * User: kang
 * Date: 2017/9/1
 * Time: 14:46
 */

namespace app\api\controller\v1;

use \app\api\model\Category as CategoryModel;
use app\lib\exception\CategoryException;

class Category extends  BaseController
{

    public function getAllCategories(){
        $categories = CategoryModel::all([],['img']);
        if(!$categories){
            throw new CategoryException();
        }
        return json($categories,200);
    }

}