<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/6/30
 * Time: 18:06
 */

namespace app\api\controller\v1\cms;
use think\Request;
// use app\api\validate\IDMustBePositiveInt;
use app\api\model\cms\CmsBelong as BelongModel;
// use app\lib\exception\BannerMissException;

class CmsBelong
{


    /*
     * 获取礼品
     * @id ture:返回id false:不返回id
     * @url cms/getGifts
     * 
     */

    public function getBelong($id=true)
    {
        header('Access-Control-Allow-Origin: *');
        header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");
        header('Access-Control-Allow-Methods: GET');
        
       
        
        // $gifts= 1;
        $belong = BelongModel::getBelong($id);

        if (empty($belong)) {
            // throw new BannerMissException();
            return [
                'err'=>'err'
            ];
        }
        return $belong;
        
    }
    
}