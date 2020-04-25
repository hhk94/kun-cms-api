<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/6/30
 * Time: 18:06
 */

namespace app\api\controller\v1;

use app\api\validate\IDMustBePositiveInt;
use app\api\model\Banner as BannerModel;
use app\lib\exception\BannerMissException;

class Banner
{


    /*
     * 获取指定id的banner信息
     * @id banner的id号
     * @url /banner
     */

    public function getBanner($id)
    {

        (new IDMustBePositiveInt())->goCheck();

        $banner = BannerModel::getBannerByID($id);

        if (empty($banner)) {
            throw new BannerMissException();
        }
        return $banner;
    }
}