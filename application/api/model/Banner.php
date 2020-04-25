<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/7/1
 * Time: 15:18
 */

namespace app\api\model;


use think\Db;
use think\Model;
use think\Exception;
use app\api\model\BaseModel;

class Banner extends BaseModel
{

        protected $hidden=['delete_time','update_time'];

//    protected $table='banner_item';
        public function items(){
            return $this->hasMany('BannerItem','banner_id','id');
        }

        public static function getBannerByID($id)
        {
            $banner = self::with(['items','items.img'])->find($id);
            return $banner;

        }


}