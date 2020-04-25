<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/9/21
 * Time: 9:32
 */

namespace app\api\validate;


class AppTokenGet extends BaseValidate
{
    protected $rule = [
      'ac'=>'require|isNotEmpty',
      'se'=>'require|isNotEmpty'
    ];
}