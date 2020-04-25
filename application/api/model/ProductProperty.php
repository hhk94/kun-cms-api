<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/7/20
 * Time: 17:23
 */

namespace app\api\model;


class ProductProperty extends BaseModel
{
    protected $hidden=[
        'delete_time','id','product_id'
    ];
}