<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/7/10
 * Time: 16:30
 */

namespace app\api\model;


use think\Model;

class BaseModel extends Model
{

    protected function prefixImgUrl($value,$date){
        $finalUrl=$value;
        if($date['from']==1){
            $finalUrl=config('setting.uploads_url').$value;
        }
        return $finalUrl;
    }

}