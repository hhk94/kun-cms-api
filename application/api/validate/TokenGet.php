<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/7/18
 * Time: 17:52
 */

namespace app\api\validate;


class TokenGet extends BaseValidate
{
    public $rule=[
        'code'=>'require|isNotEmpty'
    ];

    protected $message=[
        'code'=>'没有code'
    ];
}