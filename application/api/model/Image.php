<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/7/7
 * Time: 10:52
 */

namespace app\api\model;



class Image extends BaseModel
{
    protected $hidden=['id','from','delete_time','update_time',"create_time"];
    protected $autoWriteTimestamp = true;
    public function getUrlAttr($value,$date){
        return $this->prefixImgUrl($value,$date);
    }
    public function addImg($url,$from){
        $this->url = $url;
        $this->from = $from;
        $this->save();
        return [
            'id'=>$this->id,
           'url'=>$this->url
        ];
    }
    public function returnUrl($url,$from){
        $this->url = $url;
        $this->from = $from;
        $this->save();
        return $this->url;
    }

}