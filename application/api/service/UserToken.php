<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/7/18
 * Time: 18:01
 */

namespace app\api\service;
use app\lib\enum\ScopeEnum;
use app\lib\exception\WeChatException;
use app\api\model\User as UserModel;

class UserToken extends Token
{
    protected $code;
    protected $wxAppID;
    protected $wxAppSecret;
    protected $wxLoginUrl;

    public function __construct($code){
        $this->code=$code;
        $this->wxAppID=config('wx.app_id');
        $this->wxAppSecret=config('wx.app_secret');
        $this->wxLoginUrl = sprintf(config('wx.login_url'),$this->wxAppID,$this->wxAppSecret,$this->code);

    }

    /*获取openID*/
    public function get(){
        $result = curl_get($this->wxLoginUrl);
        $wxResult=json_decode($result,true);
        if(empty($wxResult)){
            throw new Exception('获取openID异常，微信内部错误');
        }else{
            $loginFail=array_key_exists('errcode',$wxResult);
            if($loginFail){
                    $this->processLoginError($wxResult);
            }else{
                return  $this->grantToken($wxResult);
            }
        }

    }

    private function processLoginError($wxResult){
        throw new WeChatException([
            'msg'=>$wxResult['errmsg'],
            'errorCode'=>$wxResult['errcode']
        ]);
    }

    /*总方法，创造令牌*/
    private function grantToken($wxResult){
        //拿到openID
        //数据库查看 openID是否已经存在
        //如果存在，则不处理，如果不存在，新增一条user记录
        //生成令牌，准备缓存数据，写入缓存
        //把令牌返回到客户端
        //key:令牌
        //value:wxResult,uid,scope
        $openid=$wxResult['openid'];
        $user=UserModel::getByOpenID($openid);
        if($user){
            $uid=$user->id;
        }else{
            $uid = $this->newUser($openid);
        }

        $cachedValue = $this->prepareCachedValue($wxResult,$uid);
        $token = $this->saveToCache($cachedValue);
        return $token;

    }

    /*新增使用者*/
    private function newUser($openid){
        $user=UserModel::create([
            'openid'=>$openid
        ]);
        return $user->id;
    }
    /*生成令牌的value*/
    private function prepareCachedValue($wxResult,$uid){
        $cachedValue= $wxResult;
        $cachedValue['uid']=$uid;
        /*scope = 16;代表app用户权限数值*/
        $cachedValue['scope']=ScopeEnum::User;
        return $cachedValue;

    }
    /*存入缓存*/
    private function saveToCache($cachedValue){
        $key = self::generateToken();
        $value=json_encode($cachedValue);
        $expire_in=config('setting.token_expire_in');

        $request = cache($key,$value,$expire_in);
        if(!$request){
            throw new TokenException([
                'msg'=>'服务器缓存异常',
                'errorCode'=>'10005'
            ]);
        }
        return $key;
    }


}