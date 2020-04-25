<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/7/1
 * Time: 15:18
 */

namespace app\api\model\cms;


use think\Db;
use think\Model;
use think\Exception;
use app\api\model\BaseModel;
use traits\model\SoftDelete;
class CmsNewsSettings extends BaseModel
{
    use SoftDelete;
    protected $deleteTime = 'delete_time';
    protected $hidden=['delete_time','update_time','create_time'];
    protected $autoWriteTimestamp = true;

    public static function getNews($id)
        {
            
            
            if($id=='true'){
               
                $News = self::order('id')
                ->select();
                return $News;
            }else{
                
                $News = self::order('id')->column('newsname');
                return $News;
            }
            

        }

   
        


}