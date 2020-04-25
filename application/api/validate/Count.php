<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/7/12
 * Time: 16:31
 */

namespace app\api\validate;


class Count extends BaseValidate
{
    protected $rule=[
      'count'=>'isPositiveInteger|between:1,20'
    ];
}