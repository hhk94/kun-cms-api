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
use app\api\model\cms\CmsGiftsSettings as GiftsModel;
// use app\lib\exception\BannerMissException;


class CmsGiftsSettings
{

      /*
    #cms
    get
    @url /cms/giftsDelete
    删除
    */ 
    public function giftsDelete($id){
        header('Access-Control-Allow-Origin: *');
        header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");
        header('Access-Control-Allow-Methods: PUT,POST,GET,DELETE,OPTIONS');
        $delete = GiftsModel::destroy($id);
        
        return $delete;
    }


    /*
     * 获取礼品
     * @id ture:返回id false:不返回id
     * @url cms/getGifts
     * 
     */

    public function getGifts($id=true)
    {
        header('Access-Control-Allow-Origin: *');
        header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");
        header('Access-Control-Allow-Methods: GET');
        
       
        
        // $gifts= 1;
        $gifts = GiftsModel::getGifts($id);

        if (empty($gifts)) {
            // throw new BannerMissException();
            return [
                'err'=>'err'
            ];
        }
        return $gifts;
        
    }
    public function addGifts($giftname){
        header('Access-Control-Allow-Origin: *');
        header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");
        header('Access-Control-Allow-Methods: PUT,POST,GET,DELETE,OPTIONS');
        
        $gifts = new GiftsModel();
        $gifts->giftname = $giftname;
        if($gifts->save()){
            return true;
        }
        
    }
}