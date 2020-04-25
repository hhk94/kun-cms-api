<?php
/**
  * Created by hkun
  * Email: 350839123@qq.com
  * Date: 2020-04-21
  */
namespace app\api\model\v2\cms;
use app\api\model\BaseModel;
use traits\model\SoftDelete;
class CmsArticleComments extends BaseModel {
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
	  * 方法说明 - 新增文章评论
	  */
	public function article_comments_add($article_id,$tid,$comment_content,$user_id){
		$this->article_id = $article_id;
		$this->tid = $tid;
		$this->comment_content = $comment_content;
		$this->user_id = $user_id;
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
	/**
	  * 方法说明 - 递归tid查询上级评论
	  */
	public function article_comments_tid($item){
		$result = self::with(['belongUser'])->where('id',$item['tid'])->find();
		return $result;
	}
	
}
?>