<?php 
/**
  * Created by hkun
  * Email: 350839123@qq.com
  * Date: 2020-04-13
  */

namespace app\api\model\v2\cms;
use app\api\model\BaseModel;

class CmsArticle extends BaseModel {
	protected $hidden=['delete_time','create_time'];
	protected $autoWriteTimestamp = true;
	protected $type = [
		'per_page'=>'integer'
	];
	/**
	  * 方法说明 - 模型关联 文章类别
	  */
	public function belongArticleType(){
	    return $this->hasOne('CmsArticleType','id','article_type_id');
	}
	/**
	  * 方法说明 - 模型关联 文章作者
	  */
	public function belongUser(){
	    return $this->hasOne('CmsUser','id','article_input_id');
	}
	/**
	  * 方法说明 - 模型关联 文章评论
	  */
	public function hasComments(){
	    return $this->hasMany('CmsArticleComments','article_id','id');
		
	}
	/**
	  * 方法说明	新增文章
	  * @method post
	  */
	public function add_article($article_title,$article_type_id,$article_content,$article_html,$article_input_id)
	{
	    $this->article_title = $article_title;
	    $this->article_type_id = $article_type_id;
	    $this->article_content = $article_content;
	    $this->article_html = $article_html;
	    $this->article_input_id = $article_input_id;
		if($this->save()){
			return true;
		}else{
			return false;
		}
	}
	/**
	  * 方法说明 - 查询文章列表
	  * @method get
	  */
	public function article_list_get($size,$page){
		$article_type = self::with(['belongArticleType','belongUser'])->withCount( 'hasComments')->order('update_time','desc')
		->paginate($size, true, ['page' => $page]);;
		return $article_type;
	}
	/**
	  * 方法说明 - 查询文章列表 - 根据评论数量从高到低排列
	  * @method get
	  */
	public function article_list_get_by_count($limit=10){
		$article_type = self::withCount( 'hasComments')->order('has_comments_count','desc')
		->limit($limit)->select()->hidden(['update_time','article_html','article_type_id','article_content']);
		return $article_type;
	}
	
	/**
	  * 方法说明 - 查询文章
	  * @method get
	  */
	public function article_get($id){
		$article = self::with(['belongArticleType','belongUser'])->find($id);
		return $article;
	}
}
?>