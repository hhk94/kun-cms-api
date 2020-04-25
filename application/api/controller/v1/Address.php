<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/8/1
 * Time: 11:09
 */

namespace app\api\controller\v1;



use app\api\controller\BaseController;
use app\api\validate\AddressNew;
use app\api\service\Token as TokenService;
use app\api\model\User as UserModel;
use app\api\model\UserAddress;

use app\lib\exception\SuccessMessage;

use app\lib\exception\UserException;
/*
 * @url /address
 * */
class Address extends BaseController
{
    protected $beforeActionList = [
        'checkPrimaryScope'=>['only'=>'createOrUpdateAddress,getUserAddress']
    ];



    public function createOrUpdateAddress(){
        $validate=new AddressNew();
        $validate->goCheck();
        //根据token获取uid
        //根据uid查找用户数据，判断用户是否存在，如果不存在，抛出异常
        //获取用户从客户端提交来的地址信息
        //根据用户地址信息是否存在，从而判断是添加地址还是更新地址

        $uid =TokenService::getCurrentUid();
        $user = UserModel::get($uid);

        if(!$user){
            throw new UserException();
        }

        $dataArray=$validate->getDataByRule(input('post.'));

        $userAddress=$user->address;
        if(!$userAddress){
            $user->address()->save($dataArray);
        }else{
            $user->address->save($dataArray);
        }
//        return $user;
        return json(new SuccessMessage(),201);
    }

    /**
     * 获取用户地址信息
     * @return UserAddress
     * @throws UserException
     */
    public function getUserAddress(){
        $uid = TokenService::getCurrentUid();
        $userAddress = UserAddress::where('user_id', $uid)
            ->find();
        if(!$userAddress){
            throw new UserException([
                'msg' => '用户地址不存在',
                'errorCode' => 60001
            ]);
        }
        return $userAddress;
    }


}