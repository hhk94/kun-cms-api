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

class CmsActivated extends BaseModel
{

    protected $hidden=['delete_time','update_time','create_time','top_id','bg_id'];
    protected $autoWriteTimestamp = true;

    public function imgs(){
        return $this->hasMany('CmsActivatedImage','activated_id','id');
    }

    public function topimg(){
        return $this->belongsTo('app\api\model\Image','top_id','id');
    }
    public function bgimg(){
        return $this->belongsTo('app\api\model\Image','bg_id','id');
    }
    /**
     * 查看所有活动页
     */
    public static function getAll($page=1,$size=15){
        
        
        $products = self::with([
            'imgs'=>function($query){
                $query->with(['imgUrl'])->order('order','asc');
            },'top_img','bg_img'])->order('create_time desc')
            ->paginate($size, true, ['page' => $page]);
        return $products ;
    }

     /**
     * 查看所有活动页
     */
    public static function getOne($id=1){
        
        
        $products = self::with([
            'imgs'=>function($query){
                $query->with(['imgUrl'])->order('order','asc');
            },'top_img','bg_img'])->order('create_time desc')
            ->where('id',$id)->find();
        return $products ;
    }

    
        


}