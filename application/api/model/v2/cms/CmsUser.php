<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/9/21
 * Time: 9:36
 */

namespace app\api\model\v2\cms;
use app\api\model\BaseModel;

class CmsUser extends BaseModel
{
    protected $hidden=['delete_time','update_time','create_time','app_secret'];
    protected $autoWriteTimestamp = true;
	
	/**
	  * 方法说明 - 模型关联 文章评论
	  */
	public function hasComments(){
	    return $this->hasMany('CmsArticleComments','user_id','id');
		
	}
	/**
	  * 方法说明 - 模型关联 文章评论
	  */
	public function avatarImg(){
	    return $this->hasOne('app\api\model\Image','id','avatar_img_id')->bind(['avatar_img_url'=> 'url']);
		
	}
    // protected $type = [
       
    //     'nick_name'  =>  'array',
    // ];
    public static function token_check($ac)
    {
        $app = self::with(['avatarImg'])->where('app_id','=',$ac)->find()->hidden(['avatar_img']);
        return $app;

    }
	
	//查找账号是否存在
	public static function for_check($ac)
	{
	    $app = self::where('app_id','=',$ac)->find();
		if($app){
			$app = self::where('app_id','=',$ac)->find()->visible(['app_secret','id']);
			return $app;
		}
	    return  $app;
	
	}
	//新增用户
    public function add_user($appid,$appsecret,$app_description,$scope,$scope_description,$nickname){
        $this->app_id = $appid;//账号
        $this->app_secret = $appsecret;//密码
        $this->avatar_img_id = 1;//默认头像
        $this->app_description = $app_description;//用户注册的app备注
        $this->scope = $scope;//用户权限
        $this->scope_description = $scope_description;//权限描述
        $this->nick_name = $nickname;//用户昵称
        $this->save();
        return $this->id;
    }
	//x修改用户资料
	public function change_user($id,$avatar_img_id,$new_appsecret,$nick_name){
		$user = self::get($id);
		if($avatar_img_id){
			$user->avatar_img_id = $avatar_img_id;
		}
		if($new_appsecret){
			$user->app_secret = $new_appsecret;
		}
		if($nick_name){
			$user->nick_name = $nick_name;
		}
		return $user->save();
	}
	//根据评论数查看用户列表
	public function user_list_get_by_count($limit){
		$result = self::withCount( 'hasComments')->order('has_comments_count','desc')->limit($limit)->select()
		->hidden(['scope','scope_description','app_id','app_description']);
		return $result;
	}
	
	public function user_list_get($page,$size){
		$result = self::withCount( 'hasComments')->with(['avatarImg'])->order('id','desc')->paginate($size, true, ['page' => $page]);
		return $result;
	}
	public function user_get($id){
		$result = self::with(['avatarImg'])->where('id',$id)->find();
		return $result;
	}

}