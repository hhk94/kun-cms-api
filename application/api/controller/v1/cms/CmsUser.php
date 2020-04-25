<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/6/30
 * Time: 18:06
 */

namespace app\api\controller\v1\cms;
use think\Request;
use app\api\model\ThirdApp as UserModel;
use app\api\model\cms\CmsBelong as CmsBelongModel;

use app\lib\enum\ScopeEnum;



class CmsUser
{
    /*
    *获取小组组长分类
    method:get
    @url /api/v1/cms/getAllLeader
    */ 
    public function getAllLeader (){
        header('Access-Control-Allow-Origin: *');
        header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");
        header('Access-Control-Allow-Methods: PUT,POST,GET,DELETE,OPTIONS');
        
        $usermodel = new UserModel();
        $scope = ScopeEnum::zuzhang;
        $leader =  $usermodel->where('scope', $scope)->select(); 
        return $leader;
    }

    /*
    *获取所有管理员
    method:get
    @url /api/v1/cms/getAllAdmin
    */ 
    public function getAllAdmin ($page=1,$size=15){
        header('Access-Control-Allow-Origin: *');
        header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");
        header('Access-Control-Allow-Methods: PUT,POST,GET,DELETE,OPTIONS');
        
        $usermodel = new UserModel();
        $all =  $usermodel->hidden(['tid','app_description','scope'])->order('id desc')
        ->paginate($size, true, ['page' => $page]);; 
        $count = UserModel::order('id desc')->count();
        if ($all->isEmpty())
        {
            return [
                'count'=>"",
                'current_page' => $all->getCurrentPage(),
               
                'data' => []
            ];
        }
        // return $all;
        $data =  $all->toArray();
        return [
            'count'=> $count,
            'current_page' => $all->getCurrentPage(),
            'data' => $data
        ];
    }
    /**
     * /cms/changePassword
     */
    public function changePassword(){
        header('Access-Control-Allow-Origin: *');
        header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");
        header('Access-Control-Allow-Methods: PUT,POST,GET,DELETE,OPTIONS');
        if(request()->isPost()){
            $input=input('post.');
            $oldsecret = $input['oldsecret'];
            $newsecret = $input['newsecret'];
            $nickname = $input['nickname'];
            $usermodel = new UserModel();
            $user = $usermodel->where('app_secret', $oldsecret)->where('nick_name',$nickname)
            ->find();
            if($user){
                $user->app_secret = $newsecret;
                return $user->save();
            }else{
                return 0;
            }
        }
    }

    public function userAdd (){
        header('Access-Control-Allow-Origin: *');
        header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");
        header('Access-Control-Allow-Methods: PUT,POST,GET,DELETE,OPTIONS');
        if(request()->isPost()){
            $input=input('post.');
            
            $appid = $input['appid'];
            $appsecret = $input['appsecret'];
            $nickname = $input['nickname'];
            $scope = $input['scope'];
            $tid = $input['tid'];
            $app_description = 'CMS';
            if($scope == ScopeEnum::zuzhang){
                $scope_description = '组长';
            }else if($scope == ScopeEnum::kefu){
                $scope_description = '客服';
            }else if($scope == ScopeEnum::fxshang){
                $scope_description = '分销商';
            }else if($scope == ScopeEnum::meigong){
                $scope_description = '美工';
            }else if($scope == ScopeEnum::shencha){
                $scope_description = '审查';
            }else if($scope == ScopeEnum::Super){
                $scope_description = 'Super';
            }
            $usermodel = new UserModel();
            $result = $usermodel->addUser($appid,$appsecret,$app_description,$scope,$scope_description,$nickname,$tid);
            if($result){
                //$result = userid
                $belong = new  CmsBelongModel();
                $belongs = $belong->addBelong($nickname,$result);
                return $belongs;
            }else{
                return '添加失败';
            }
        }
    }
} 