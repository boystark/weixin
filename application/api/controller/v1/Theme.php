<?php

namespace app\api\controller\v1;

use app\api\validate\IDCollection;
use app\api\validate\IDMustBePositiveInt;

use app\lib\exception\ThemeException;

use \app\api\model\Theme as ThemeModel;
class Theme extends BaseController
{

    /**
     * @return $ids
     * url api/version/theme?ids=1,2,3,...
     */
    public function getSimpleList($ids = '')
    {
        (new IDCollection())->goCheck();
        $result = ThemeModel::getThemeByIDs($ids);
        if(!$result){
            throw new ThemeException();
        }
        return json($result,200);
    }

    public function getComplexOne($id){
        (new IDMustBePositiveInt())->goCheck();
        $result = ThemeModel::getThemeByID($id);
        if(!$result){
            throw new ThemeException();
        }
        return json($result,200);
    }

}
