<?php
/**
  * Created by hkun
  * Email: 350839123@qq.com
  * Date: 2020-04-13
  */

namespace app\api\controller\v2\cms;
use think\Request;

use app\api\model\v2\cms\CmsArticle as CmsArticleModel;

use app\api\model\Image as ImageModel;

use app\api\model\cms\CmsUsernewsImage as UsernewsImageModel;

use app\lib\enum\StateEnum;
use app\api\validate\IDMustBePositiveInt;
class CmsArticle {
	
   /**
     * 方法说明 - 文章中图片添加
     * @param file
     * @url /api/v2/cms/article_add_img
     * @method post
     */
	public function article_add_img(){
	    $files = request()->file('file');
	    $index = 0;
	    // return $files;
		if($files){
			foreach($files as $file){
			    // 移动到框架应用根目录/public/uploads/ 目录下
			    $info = $file->move(ROOT_PATH . 'public' . DS . 'uploads');
			    $path = ROOT_PATH . 'public' . DS . 'uploads';
				
			    if($info){
			        $index++;
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
	  * 方法说明 - 文章添加
	  * @url /api/v2/cms/article_add
	  * @param article_id
	  * @param article_title
	  * @param article_type_id
	  * @param article_content
	  * @param article_html
	  * @param article_input_id
	  * @method post
	  */
	public function article_add(){
		if(request()->isPost()){
			$input=input('post.');
			$article_id = $input['article_id'];//文章id
			$article_title = $input['article_title'];//文章标题
			$article_type_id = $input['article_type_id'];//文章类别
			$article_content = $input['article_content'];//文章内容
			$article_html = $input['article_html'];//文章内容
			$article_input_id = $input['article_input_id'];//作者id
			$article = new CmsArticleModel();
			if($article_id){
				//第二次上传 直接进入修改文章
				$change = $article->allowField(true)->save($input,['id' => $article_id]);
				if($change){
					return [
						"state"=>StateEnum::success,
						'msg'=>'修改文章成功',
						'data'=>['id'=>$article_id]
					];
				}else{
					return [
					    "state"=>StateEnum::fail,
					    'msg'=>'新增失败'
					    
					];
				}
				
			}else{
				//第一次上传文章
				$result = $article->add_article($article_title,$article_type_id,$article_content,$article_html,$article_input_id);
				if($result){
					return [
					    "state"=>StateEnum::success,
					    'msg' => '新增文章成功',
						'data'=>['id'=>$article->id]
					];
				}else{
					return [
					    "state"=>StateEnum::fail,
					    'msg'=>'新增失败'
					    
					];
				};
			}
			
		}
	}
	/**
	  * 方法说明 - 获取文章列表
	  * @url /api/v2/cms/article_list_get
	  * @param {int} $size 一页个数
	  * @param {int} $page 页码
	  * @method get
	  */
	public function article_list_get($size=10,$page=1){
		$type = new CmsArticleModel();
		$result = $type->article_list_get($size,$page);
		$total = CmsArticleModel::order('id desc')->count();
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
	  * 方法说明 - 文章删除
	  * @url /api/v2/cms/article_delete
	  * @param {int} id
	  * @method delete
	  */
	public function article_delete($id){
		(new IDMustBePositiveInt())->goCheck();
		$delete = CmsArticleModel::destroy($id);
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
	/**
	  * 方法说明 - 单篇文章获取
	  * @url /api/v2/cms/article_get
	  * @param {int} id
	  * @method get
	  */
	public function article_get($id){
		(new IDMustBePositiveInt())->goCheck();
		$type = new CmsArticleModel();
		$result = $type->article_get($id);
		if($result){
			return [
				'state'=>StateEnum::success,
				'msg'=>'查询成功',
				'data'=>$result
			];
		}else{
			return [
				'state'=>StateEnum::fail,
				'msg'=>'删除失败'
			];
		};
	}
	
	/**
	  * 方法说明 - 查询文章列表 - 根据评论数量从高到低排列
	  * @method get
	  */
	public function article_list_get_by_count($limit=10){
		$type = new CmsArticleModel();
		$result = $type->article_list_get_by_count($limit);
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
}


?>