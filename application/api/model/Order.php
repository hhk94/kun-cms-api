<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/8/19
 * Time: 10:41
 */

namespace app\api\model;


class Order extends BaseModel
{
        protected $hidden = ['user_id','delete_time','update_time'];
        protected $autoWriteTimestamp = true;
    //自动写入create_time、delete_time、update_time；可以手动修改字段，如下
//        protected $createTime = 'create_timestamp';

    public function getSnapItemsAttr($value){
        if(empty($value)){
            return null;
        }
        return json_decode($value);
    }

    public function getSnapAddressAttr($value){
        if(empty($value)){
            return null;
        }
        return json_decode($value);
    }

        public static function getSummaryByUser($uid,$page=1,$size=15)
        {
            //返回paginate::对象
            $paginDate = self::where('user_id','=',$uid)->order('create_time desc')
                ->paginate($size,true,['page'=>$page]);
            return $paginDate;
        }

    public static function getSummaryByPage($page=1, $size=20){
        $pagingData = self::order('create_time desc')
            ->paginate($size, true, ['page' => $page]);
        return $pagingData ;
    }
}