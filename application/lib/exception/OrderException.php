<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/8/8
 * Time: 9:49
 */

namespace app\lib\exception;


class OrderException extends BaseException
{
    public $code = 400;
    public $msg = '订单不存在，请检查ID';
    public $errorCode = 80000;
}