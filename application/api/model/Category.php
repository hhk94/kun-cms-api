<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/7/15
 * Time: 16:54
 */

namespace app\api\model;


class Category extends BaseModel
{
    protected $hidden=['delete_time','update_time','create_time'];
    protected $autoWriteTimestamp = true;
    public function Img(){
        return $this->belongsTo('Image','topic_img_id','id');
    }

    public static function getCategory(){
        $categories= self::all([],'img');
        return $categories;
    }
}