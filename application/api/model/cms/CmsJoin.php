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
class CmsJoin extends BaseModel
{
    use SoftDelete;
    protected $deleteTime = 'delete_time';
    protected $hidden=['delete_time','update_time'];
    protected $autoWriteTimestamp = true;

    /**
     * 查看所有信息
     */
    public static function getAll($page=1,$size=15){
        
        
        $products = self::order('create_time desc')
            ->paginate($size, true, ['page' => $page]);
        return $products ;
    }

   
        


}