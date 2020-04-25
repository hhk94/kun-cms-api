<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/8/1
 * Time: 15:52
 */

namespace app\lib\exception;


use app\lib\exception\BaseException;

class SuccessMessage extends BaseException
{
    public $code=201;
    public $msg='ok';
    public $errorCode=0;
}
