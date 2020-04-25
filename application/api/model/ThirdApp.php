<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/9/21
 * Time: 9:36
 */

namespace app\api\model;


class ThirdApp extends BaseModel
{
    protected $hidden=['delete_time','update_time','create_time','app_secret'];
    protected $autoWriteTimestamp = true;
    // protected $type = [
       
    //     'nick_name'  =>  'array',
    // ];
    public static function check($ac, $se)
    {
        $app = self::where('app_id','=',$ac)
            ->where('app_secret', '=',$se)
            ->find();
        return $app;

    }

    public function addUser($appid,$appsecret,$app_description,$scope,$scope_description,$nickname,$tid){
        $this->app_id = $appid;
        $this->app_secret = $appsecret;
        $this->app_description = $app_description;
        $this->scope = $scope;
        $this->scope_description = $scope_description;
        $this->nick_name = $nickname;
        $this->tid = $tid;
        $this->save();
        return $this->id;
    }


}