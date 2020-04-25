<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/6/30
 * Time: 18:06
 */

namespace app\api\controller\v2;

use app\api\validate\IDMustBePositiveInt;
use app\api\model\Banner as BannerModel;
use app\lib\exception\BannerMissException;

class Banner
{
//
//    获取指定id的banner信息
//    @id banner的id号
//    @url /banner

    public function getBanner($id)
    {
        return "this is v2";

    }
}