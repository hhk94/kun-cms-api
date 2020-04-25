<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/7/20
 * Time: 10:21
 */

namespace app\api\service;


use app\lib\enum\ScopeEnum;
use think\Cache;
use think\Request;
use think\Exception;
use app\lib\exception\TokenException;
use app\lib\exception\ForbiddenException;




class Token
{
    //生成token
    public static function generateToken(){
        //32位字符组成一组随机字符串
        $randChars= getRandChar(32);
        /*加强安全性：用三组字符串，进行MD5加密*/
        $timestamp = $_SERVER['REQUEST_TIME_FLOAT'];
        /*salt 盐*/
        $salt=config('secure.token_salt');

        return md5($randChars.$timestamp.$salt);
    }

    public static function getCurrentTokenVar($key){
        $token = Request::instance()->header('token');
        $vars = Cache::get($token);
        if(!$vars){
            throw new TokenException();
        }else{
            if(!is_array($vars)){
                $vars = json_decode($vars,true);
            }
            if(array_key_exists($key,$vars)){
                return $vars[$key];
            }else{
                throw new Exception('尝试获取的token变量不存在');
            }
        }
    }


    public static function getCurrentUid(){
        $uid=self::getCurrentTokenVar('uid');
        return $uid;
    }

    //用户和cms管理员都可以访问的权限
    public static function needPrimaryScope(){
        $scope = self::getCurrentTokenVar('scope');
        if($scope){
            if($scope>=ScopeEnum::User){
                return true;
            }else{
                throw new ForbiddenException();
            }
        }else{
            throw new TokenException();
        }
    }

    public static function needSuperUser(){
        $scope = self::getCurrentTokenVar('scope');
        if($scope){
            if($scope==ScopeEnum::Super){
                return true;
            }else{
                throw new ForbiddenException();
            }
        }
    }

    //只有用户才能访问的权限
    public static function needExclusiveScope(){
        $scope = self::getCurrentTokenVar('scope');
        if($scope){
            if($scope==ScopeEnum::User){
                return true;
            }else{
                throw new ForbiddenException();
            }
        }else{
            throw new TokenException();
        }


    }


    public static function isValidOperate($checkedUID)
    {
        if(!$checkedUID){
            throw new Exception('检测UID时必须传入一个被检测的UID');
        }
        $currentOperateUID =self::getCurrentUid();
        if($checkedUID == $currentOperateUID){
            return true;
        }
        return false;

    }

    public static function verifyToken($token)
    {
        $exist = Cache::get($token);
        if($exist){
            return true;
        }
        else{
            return false;
        }
    }


}