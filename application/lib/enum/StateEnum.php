<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/8/2
 * Time: 10:31
 */

namespace app\lib\enum;


class StateEnum
{
    //数据库添加成功
    const success = 1;
	//
    //数据库添加失败
    const fail = 2;
	//运行中条件判断失败
    const anoter_fail = 3;
}