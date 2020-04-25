<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/7/1
 * Time: 15:18
 */

namespace app\api\model\cms;
use app\api\model\BaseModel;

use think\Db;
use think\Model;
use think\Exception;
use traits\model\SoftDelete;

class CmsUsernews extends BaseModel
{
    use SoftDelete;
    protected $deleteTime = 'delete_time';

    protected $hidden=['delete_time','update_time'];
    protected $autoWriteTimestamp = true;

    public function imgs(){
        return $this->hasMany('CmsUsernewsImage','usernews_id','id');
    }
    public function belongs(){
        return $this->hasOne('CmsBelong','id','belong');
    }
    public static function searchUsernews($page=1,$size=15,$news){
        $products = self::with([
            'imgs'=>function($query){
                $query->with(['imgUrl'])->order('order','asc');
            },'belongs'
        ])->where($news)->order('create_time desc')
            ->paginate($size, true, ['page' => $page]);
        return $products ;
    }
    public static function getAllNewsByPerson($page=1,$size=15,$inputer,$tid){
        
        
        $products = self::with([
            'imgs'=>function($query){
                $query->with(['imgUrl'])->order('order','asc');
            },'belongs'
        ])->where('inputman','=',$inputer)->whereOr('belong','=',$tid)->order('create_time desc')
            ->paginate($size, true, ['page' => $page]);
        return $products ;
    }

    public static function getLeaderAll($page=1,$size=15,$kefus,$orid){
        
        
        $products = self::with([
            'imgs'=>function($query){
                $query->with(['imgUrl'])->order('order','asc');
            },'belongs'
        ])->where('inputman','in',$kefus)->whereOr('belong','in',$orid)->order('create_time desc')
            ->paginate($size, true, ['page' => $page]);
           
        return $products ;
    }



    /**
     * 审查员及以上查看所有信息
     */
    public static function getAllNewsBycheckedman($page=1,$size=15){
        
        
        $products = self::with([
            'imgs'=>function($query){
                $query->with(['imgUrl'])->order('order','asc');
            },'belongs'
        ])->order('create_time desc')
            ->paginate($size, true, ['page' => $page]);
        return $products ;
    }
    /**
     * 用于修改，查询单个信息
     */
    public static function getUsernewsDetail($id){
        $usernews=self::with([
            'imgs'=>function($query){
                $query->with(['imgUrl'])->order('order','asc');
            }
        ])->find($id);
        return $usernews;
    }
        


}