<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/7/18
 * Time: 18:00
 */

namespace app\api\model;


class User extends BaseModel
{
    public function address(){
        $userAddress = $this->hasOne('UserAddress','user_id','id');
        return $userAddress;
    }

    public static function getByOpenID($openid){
        $user=self::where('openid','=',$openid)->find();
        return $user;
    }


}