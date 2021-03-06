<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/7/20
 * Time: 17:20
 */

namespace app\api\model;


class ProductImage extends BaseModel
{
    protected $hidden=[
        'delete_time','img_id','product_id'
    ];
    protected $autoWriteTimestamp = true;

    public function imgUrl(){
        return $this->belongsTo('Image','img_id','id');
    }

    public function addImgs($list){
        // $this->product_id = $product_id;
        // $this->img_id = $img_id;
        // $this->order = $order;
        $this->saveAll($list);
        return true;
    }


}