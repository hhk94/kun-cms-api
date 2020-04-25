<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/6/30
 * Time: 18:40
 */

namespace app\api\validate;


use think\Validate;

class TestValidate extends  Validate
{
    protected  $rule=[
        'name'=>'require|max:10',
        'email'=>'email'
    ];


}