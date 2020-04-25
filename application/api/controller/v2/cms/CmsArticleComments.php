<?php
/**
  * Created by hkun
  * Email: 350839123@qq.com
  * Date: 2020-04-21
  */
namespace app\api\controller\v2\cms;
use think\Request;

use app\api\model\v2\cms\CmsArticleComments as CmsArticleCommentsModel;
use app\lib\enum\StateEnum;
use app\api\validate\IDMustBePositiveInt;
use app\api\validate\v2\CmsArticleComments as CmsArticleCommentsTest;

class CmsArticleComments {
	/**
	  * 方法说明 新增文章评论
	  * @url  /api/v2/cms/article_comments_add
	  * @param {int} article_id 文章id
	  * @param {int} tid 评论上级id
	  * @param {string} comment_content 评论内容
	  * @param {int} user_id 作者id
	  * @method post
	  */
	public function article_comments_add($article_id,$tid,$comment_content,$user_id){
		(new CmsArticleCommentsTest())->goCheck();
		$comment = new CmsArticleCommentsModel();
		$result = $comment->article_comments_add($article_id,$tid,$comment_content,$user_id);
		if($result){
			return [
			    "state"=>StateEnum::success,
			    'msg' => '新增评论成功',
			];
		}else{
			return [
			    "state"=>StateEnum::fail,
			    'msg'=>'新增评论失败'
			];
		};
	}
	/**
	  * 方法说明 获取评论列表
	  * @url  /api/v2/cms/article_comments_get
	  * @param {int} article_id 文章id
	  * @param {int} page 页码
	  * @param {int} size 个数
	  * @method 
	  */
	public function article_comments_get($article_id,$page=1,$size=10){
		$comment = new CmsArticleCommentsModel();
		$comment2 = new CmsArticleComments();
		$result = $comment->article_comments_get($article_id,$page,$size);
		$total = CmsArticleCommentsModel::where('article_id',$article_id)->order('id desc')->count();
		for($x=0;$x<count($result);$x++){
			$data = [];
			$result2 = self::loop_tid($result[$x],$data);
			$result[$x]['father_list'] = $result2 ;
			
		}
		return [
			 "state"=>StateEnum::success,
			 "data"=>$result,
			 'total'=>$total,
			 'current_page' => (int)$result->getCurrentPage(),
		];
	}
	/**
	  * 方法说明 根据tid获取上级评论
	  * @param {array} list 返回列表
	  * @param {item} 评论集
	  */
	public static function loop_tid($item,$list){
		$comment = new CmsArticleCommentsModel();
		$result2 = $comment->article_comments_tid($item);
		// $this->a=666;
		// \var_dump($result2);
		if($result2){
			array_push($list,$result2);
			if($result2['tid']!=0){
				$comment2 = new CmsArticleComments();
				return $comment2->loop_tid($result2,$list);
			}else{
				return $list;
			}
		}
	}
}
?>