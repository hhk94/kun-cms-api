<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/8/3
 * Time: 17:02
 */

namespace app\api\controller;


use think\Controller;
use app\api\service\Token as TokenService;
// Hook::listen('appInit');
// Hook::add('app_init','app\\api\\CORS'); 

class BaseController extends Controller
{
    // protected function checkPrimaryScope(){
    //     TokenService::needPrimaryScope();

    // }

    // protected function checkExclusiveScope(){
    //     TokenService::needExclusiveScope();

    // }
	//密码加密
    protected function password_encryption($string){
		return \password_hash($string,PASSWORD_DEFAULT);
	}

   //密码解密
   protected function password_check($string,$encryption){
   	return \password_verify($string,$encryption);
   }


}