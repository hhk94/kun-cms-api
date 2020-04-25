<?php 
/**
  * Created by hkun
  * Email: 350839123@qq.com
  * Date: 2020-04-13
  */
 
namespace app\api\controller\v2\cms;
use think\Request;
use app\api\model\v2\cms\CmsArticleType as ArticleTypeModel;
use app\api\model\v2\cms\CmsArticle as CmsArticleModel;
use app\lib\enum\StateEnum;
use app\api\validate\IDMustBePositiveInt;

class CmsArticleType {
	/**
	  * 方法说明 - 文章类别增加
	  * @url /api/v2/cms/article_type_add
	  * @param {string} typename
	  * @method post
	  */
	public function article_type_add($typename){
		$type = new ArticleTypeModel();
		$result = $type->add_type($typename);
		if($result){
			return [
				'state'=>StateEnum::success,
				'msg'=>'添加成功',
				'data'=>$result
			];
		}else{
			return [
				'state'=>StateEnum::fail,
				'msg'=>'添加失败'
			];
		}
	}
	/**
	  * 方法说明 - 文章类型列表查询
	  * @url /api/v2/cms/article_type_list_get
	  * @param null
	  * @method get
	  */
	public function article_type_list_get(){
		$type = new ArticleTypeModel();
		$result = $type->article_type_list_get();
		if($result){
			return [
				'state'=>StateEnum::success,
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
	  * 方法说明 - 文章类型列表删除
	  * @url /api/v2/cms/article_type_delete
	  * @param {int} id
	  * @method delete
	  */
	public function article_type_delete($id){
		(new IDMustBePositiveInt())->goCheck();
		$result = CmsArticleModel::where('article_type_id',$id)->select();
		if(\count($result)>0){
			return [
				'state'=>StateEnum::fail,
				'msg'=>'该分类下存在多个article未删除'
			];
		}
		$delete = ArticleTypeModel::destroy($id);
		if($delete){
			return [
				'state'=>StateEnum::success,
				'msg'=>'删除成功'
			];
		}else{
			return [
				'state'=>StateEnum::fail,
				'msg'=>'删除失败'
			];
		};
	}
}
?>