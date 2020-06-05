<?php
/**
  * Created by hkun
  * Email: 350839123@qq.com
  * Date: 2020-04-21
  */
namespace app\api\model\v2\cms;
use app\api\model\BaseModel;
use traits\model\SoftDelete;
class CmsRouter extends BaseModel {
	use SoftDelete;
	protected $deleteTime = 'delete_time';
	protected $hidden=['delete_time','create_time'];
	protected $autoWriteTimestamp = true;
	/**
	  * 方法说明 - 模型关联 文章作者
	  */
	public function belongUser(){
	    return $this->hasOne('CmsUser','id','user_id');
	}
	
	/**
	  * 方法说明 - 新增路由
	  */
	public function  router_add($route,$router_name,$title,$icon,$file_path,$in_nav,$level,$pre_id,$need_login){
		$this->route = $route;
		$this->router_name = $router_name;
		$this->title = $title;
		$this->icon = $icon;
		$this->file_path = $file_path;
		$this->in_nav = $in_nav;
		$this->level = $level;
		$this->pre_id = $pre_id;
		$this->need_login = $need_login;
		if($this->save()){
			return true;
		}else{
			return false;
		}
	}
	/**
	  * 方法说明 - 查询文章评论列表
	  */
	public function article_comments_get($article_id,$page,$size){
		$article_type = self::with(['belongUser.avatarImg'])->order('update_time','asc')->where('article_id',$article_id)
		->paginate($size, true, ['page' => $page]);;
		return $article_type;
	}
	
	public function router_list_pages($size,$page){
		$list = self::order('id','asc')->paginate($size, true, ['page' => $page]);
		return $list;
	}
	
}
?>