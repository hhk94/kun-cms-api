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
use app\api\model\cms\CmsNewsSettings as NewsModel;
// use app\lib\exception\BannerMissException;

class CmsNewsSettings
{


      /*
    #cms
    get
    @url /cms/newsDelete
    删除
    */ 
    public function newsDelete($id){
        header('Access-Control-Allow-Origin: *');
        header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");
        header('Access-Control-Allow-Methods: PUT,POST,GET,DELETE,OPTIONS');
        $delete = NewsModel::destroy($id);
        
        return $delete;
    }
    /*
     * 获取礼品
     * @id ture:返回id false:不返回id
     * @url cms/getNews
     * 
     */

    public function getNews($id=true)
    {
        header('Access-Control-Allow-Origin: *');
        header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");
        header('Access-Control-Allow-Methods: GET');
        
       
        
        // $gifts= 1;
        $News = NewsModel::getNews($id);

        if (empty($News)) {
            // throw new BannerMissException();
            return [
                'err'=>'err'
            ];
        }
        return $News;
        
    }
    public function addNews($newsname){
        header('Access-Control-Allow-Origin: *');
        header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");
        header('Access-Control-Allow-Methods: PUT,POST,GET,DELETE,OPTIONS');
        
        $News = new NewsModel();
        $News->newsname = $newsname;
        if($News->save()){
            return true;
        }
        
    }
}