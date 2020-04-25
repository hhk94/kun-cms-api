<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/7/20
 * Time: 17:20
 */

namespace app\api\model\cms;
use app\api\model\BaseModel;

class CmsUsernewsImage extends BaseModel
{
    protected $hidden=[
        'delete_time','img_id','usernews_id','create_time','update_time'
    ];
    protected $autoWriteTimestamp = true;

    public function imgUrl(){
        return $this->belongsTo('app\api\model\Image','img_id','id');
    }

    public function addImgs($list){
        // $this->product_id = $product_id;
        // $this->img_id = $img_id;
        // $this->order = $order;
        $this->saveAll($list);
        return true;
    }


}