<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/9/21
 * Time: 9:32
 */

namespace app\api\validate\v2;
use app\api\validate\BaseValidate;

class AppTokenGet extends BaseValidate
{
    protected $rule = [
      'appid'=>'require|isNotEmpty',
      'appsecret'=>'require|isNotEmpty'
    ];
}