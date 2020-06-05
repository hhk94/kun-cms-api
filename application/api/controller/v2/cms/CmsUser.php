<?php
/**
 * Created by PhpStorm.
 * User: hkun
 * Date: 2020/4/13
 * Time: 18:06
 */
namespace app\api\controller\v2\cms;
use think\Request;
use app\api\model\v2\cms\CmsUser as UserModel;
use app\api\model\cms\CmsBelong as CmsBelongModel;
use app\api\controller\BaseController as BaseController;
use app\lib\enum\ScopeEnum;
use app\lib\enum\StateEnum;
use app\api\service\v2\AppToken;
use app\api\validate\v2\AppTokenGet;
use app\api\model\Image as ImageModel;

class CmsUser extends BaseController
{
  

    /*
    *获取所有管理员
    method:get
    @url /api/v1/cms/getAllAdmin
    */ 
    // public function getAllAdmin ($page=1,$size=15){
    //     header('Access-Control-Allow-Origin: *');
    //     header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");
    //     header('Access-Control-Allow-Methods: PUT,POST,GET,DELETE,OPTIONS');
        
    //     $usermodel = new UserModel();
    //     $all =  $usermodel->hidden(['tid','app_description','scope'])->order('id desc')
    //     ->paginate($size, true, ['page' => $page]);; 
    //     $count = UserModel::order('id desc')->count();
    //     if ($all->isEmpty())
    //     {
    //         return [
    //             'count'=>"",
    //             'current_page' => $all->getCurrentPage(),
               
    //             'data' => []
    //         ];
    //     }
    //     // return $all;
    //     $data =  $all->toArray();
    //     return [
    //         'count'=> $count,
    //         'current_page' => $all->getCurrentPage(),
    //         'data' => $data
    //     ];
    // }
    /**
     * /cms/changePassword
     */
    // public function changePassword(){
    //     header('Access-Control-Allow-Origin: *');
    //     header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");
    //     header('Access-Control-Allow-Methods: PUT,POST,GET,DELETE,OPTIONS');
    //     if(request()->isPost()){
    //         $input=input('post.');
    //         $oldsecret = $input['oldsecret'];
    //         $newsecret = $input['newsecret'];
    //         $nickname = $input['nickname'];
    //         $usermodel = new UserModel();
    //         $user = $usermodel->where('app_secret', $oldsecret)->where('nick_name',$nickname)
    //         ->find();
    //         if($user){
    //             $user->app_secret = $newsecret;
    //             return $user->save();
    //         }else{
    //             return 0;
    //         }
    //     }
    // }

	/*
	*	@param：blog用户新增 - 默认为普通用户
		@method:post
		@url /api/v2/cms/add_user
	*/
    public function add_user(){
        if(request()->isPost()){
            $input=input('post.');
            $appid = $input['appid'];//账号
            $appsecret = $input['appsecret'];//密码
			if($appsecret==''){
				return [
					'state'=>StateEnum::anoter_fail,
					'msg'=>'失败,密码不能为空'
				];
			}
            $nickname = '用户'.getRandChar(16);//用户昵称
            $scope = ScopeEnum::User; //默认都是普通用户	
            $app_description = 'BLOG';//默认注册的是微博
            if($scope == ScopeEnum::User){
                $scope_description = '普通用户';
            }else if($scope == ScopeEnum::Super){
                $scope_description = '超级管理员';
            }
			$appsecret = $this->password_encryption($appsecret);//密码加密
			// $appsecret2 = $this->password_encryption($appsecret);//密码加密
			
			// return [
			// 	'se1'=>$appsecret,
			// 	'jiami'=>$appsecret2,
			// 	'duibi'=>$this->password_check($appsecret,'$2y$10$T45YoR/KcaQ8hHF89IAYw.TGfrz7evnO2hEdFe9HPN5grNglbByiO')
			// ];
            $usermodel = new UserModel();
			$check_result = $usermodel->for_check($appid);
			if($check_result){//判断是否已经注册该账号
				return [
					'state'=>StateEnum::anoter_fail,
					'msg'=>'失败,该账号已经注册'
				];
			}
            $result = $usermodel->add_user($appid,$appsecret,$app_description,$scope,$scope_description,$nickname);
            if($result){
                return [
					'state'=>StateEnum::success,
					'msg'=>'添加成功',
					'result'=>[
						'appid'=>$appid,
						'appsecret'=>$input['appsecret']
					],
					'uid'=>$result
				];
            }else{
               return [
               	'state'=>StateEnum::fail,
               	'msg'=>'添加失败',
               	'data'=>$result
               ];
            }
        }else{
			return [
				'se1'=>'缺少参数'
				
			];
		}
    }
	
	/*
	*	@param：blog用户修改头像上传 - 返回图片id
	*	@method:post
	*	@url /api/v2/cms/add_user_avatar
	*/
	public function add_user_avatar(){
		$file = request()->file('file');
		if($file){
			// 移动到框架应用根目录/public/uploads/ 目录下
			$info = $file->move(ROOT_PATH . 'public' . DS . 'uploads');
			$path = ROOT_PATH . 'public' . DS . 'uploads';
			if($info){
			    $ImageModel = new  ImageModel();
				$getSaveName=str_replace("\\","/",$info->getSaveName());
			    $img= $ImageModel->addImg("/".$getSaveName,$form=1);
			    $id=$img['id'];
				$data = $img['url'];
			}else{
			    // 上传失败获取错误信息
			    // echo $file->getError();
			    $data[]=$file->getError();
			}     
			return [
			    "state"=>StateEnum::success,
			    'id'=>$id,
			    'data' => $data
			];
		}else{
			return [
			    "state"=>StateEnum::fail,
			    'msg'=>'没有文件'
			    
			];
		}
	}
	/**
	  * 方法说明 - 用户登录 - 获取token
	  * @url /api/v2/cms/user_login
	  * @param {varchar} appid 
	  * @param {varchar} appsecret 
	  * @method post
	  */
	public function user_login (){
		
		if(request()->isPost()){
			$input=input('post.');
			$appid = $input['appid'];//账号
			$appsecret = $input['appsecret'];//密码
			$usermodel = new UserModel();
			$check_result = $usermodel->for_check($appid);
			if($check_result){//账号存在
				$result = $this->password_check($appsecret,$check_result['app_secret']);
				if($result){
					(new AppTokenGet())->goCheck();
					$app = new AppToken();
					$token = $app->get($appid, $appsecret);
					return [
						'state'=>StateEnum::success,
						'msg' => '登录成功!',
					    'data' => $token
					];
				}else{
					return [
						'state'=>StateEnum::fail,
					    'msg' => '密码错误!'
					];
				}
			}else{
				return [
					'state'=>StateEnum::fail,
				    'msg' => '账号不存在!'
				];
			};
		}
	}
	/**
	  * 方法说明 - 根据用户评论数从高到底获取列表
	  * @url /api/v2/cms/user_list_get_by_count
	  * @param {varchar} limit 获取数量 默认10 
	  * @method get
	  */
	public function user_list_get_by_count($limit=10){
		$usermodel = new UserModel();
		$result = $usermodel->user_list_get_by_count($limit);
		if($result){
			return [
				'state'=>StateEnum::success,
				'msg'=>'查询成功',
				'data'=>$result
			];
		}else{
			return [
				'state'=>StateEnum::fail,
				'msg'=>'查询失败'
			];
		};
	}
	/**
	  * 方法说明 - 获取列表
	  * @url /api/v2/cms/user_list_get
	  * @param {int} page
	  * @param {int} size
	  * @method get
	  */
	public function user_list_get($page=1,$size=10){
		$usermodel = new UserModel();
		$result = $usermodel->user_list_get($page,$size);
		$total = UserModel::order('id desc')->count();
		if($result){
			return [
				'state'=>StateEnum::success,
				'data'=>$result,
				'total'=>$total,
				'current_page' => (int)$result->getCurrentPage(),
			];
		}else{
			return [
				'state'=>StateEnum::fail,
				'msg'=>'查询失败'
			];
		};
	}
	/**
	  * 方法说明 - 用户修改资料
	  * @url /api/v2/cms/user_change
	  * @param {int} id 数据库id
	  * @param {int} avatar_img_id 头像id
	  * @param {varchar} appid 账号
	  * @param {varchar} new_psd 新密码
	  * @param {varchar} psd 旧密码
	  * @param {varchar} nick_name 昵称
	  * @method post
	  */
	public function user_change(){
		if(request()->isPost()){
			$input=input('post.');
			$id = $input['id'];//id
			$avatar_img_id = $input['avatar_img_id'];//头像
			$appid = $input['appid'];//账号
			$appsecret = $input['psd'];//密码
			$nick_name = $input['nick_name'];//昵称
			$new_psd = $input['new_psd'];//新密码
			$new_appsecret ='';
			$usermodel = new UserModel();
			$check_result = $usermodel->for_check($appid);//判断账号是否存在，存在返回验证密码
			if(!$check_result){
				return [
					'state'=>StateEnum::fail,
				    'msg' => '账号不存在!'
				];
			}else if($check_result['id']!=$id){
				return [
					'state'=>StateEnum::fail,
				    'msg' => 'id不匹配!'
				];
			}
			$app = new AppToken();
			if($new_psd){//修改密码 - 1.验证旧密码
				$result = $this->password_check($appsecret,$check_result['app_secret']);//获取密码与数据库解密对比
				if($result){
					//验证通过 - 新密码加密
					$new_appsecret = $this->password_encryption($new_psd);//密码加密
					$change_ok = $usermodel->change_user($id,$avatar_img_id,$new_appsecret,$nick_name);
					if($change_ok){
						$token = $app->get($appid, $new_appsecret);
						return [
							'state'=>StateEnum::success,
						    'msg' => '修改成功!',
							'change_psd'=>true,
							'data' => $token
						];
					}else{
						return [
							'state'=>StateEnum::fail,
						    'msg' => '修改失败!'
						];
					}
				}else{
					return [
						'state'=>StateEnum::fail,
					    'msg' => '密码错误!'
					];
				}
			}
			$change_ok = $usermodel->change_user($id,$avatar_img_id,$new_appsecret,$nick_name);
			if($change_ok){
				
				$token = $app->get($appid, $new_appsecret);
				
				return [
					'state'=>StateEnum::success,
				    'msg' => '修改成功!',
					'change_psd'=>false,
					'data' => $token
				];
			}else{
				return [
					'state'=>StateEnum::fail,
				    'msg' => '修改失败!'
				];
			}
		}
	}
	/**
	  * 方法说明 - 获取单个用户
	  * @url /api/v2/cms/user_get
	  * @param {int} id
	  * @method get
	  */
	public function user_get($id){
		$usermodel = new UserModel();
		$result = $usermodel->user_get($id);
		if($result){
		    return [
				'state'=>StateEnum::success,
				'msg'=>'查询成功',
				'data'=>$result
			];
		}else{
		   return [
		   	'state'=>StateEnum::fail,
		   	'msg'=>'查询失败'
		   ];
		}
	}
} 