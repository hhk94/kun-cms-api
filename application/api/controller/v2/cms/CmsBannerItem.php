<?php
 /**
   * Created by hkun
   * Email: 350839123@qq.com
   * Date: 2020-04-24
   */
namespace app\api\controller\v2\cms;
use think\Request;
use app\api\model\v2\cms\CmsBannerItem as CmsBannerItemModel;
use app\lib\enum\StateEnum;
use app\api\validate\IDMustBePositiveInt;
use app\api\model\Image as ImageModel;
class CmsBannerItem {
	/**
	  * 方法说明 - banner类别添加
	  * @url /api/v2/cms/banenr_add
	  * @param {string} id 存在id即进入修改
	  * @param {string} banner_name banner名称
	  * @param {string} banner_type_id banner类别
	  * @param {string} jump_url 跳转url
	  * @param {int} banner_img_id 图片id
	  * @method post
	  */
	public function banenr_add($id,$banner_name,$banner_type_id,$jump_url,$banner_img_id){
		$type = new CmsBannerItemModel();
		if($id){//传入id 进入修改
			$result = $type->where('id',$id)->find();//查询数据库
			if($result){//数据存在
				$result->banner_name = $banner_name;
				$result->banner_type_id = $banner_type_id;
				if($banner_img_id){
					$result->banner_img_id = $banner_img_id;
				}
				$result->jump_url = $jump_url;
				if($result->save()){//进入修改
					return [
						'state'=>StateEnum::success,
						'msg'=>'修改成功',
						'data'=>$result
					];
				}else{
					return [
						'state'=>StateEnum::fail,
						'msg'=>'修改失败'
					];
				}
			}else{//数据不存在
				return [
					'state'=>StateEnum::fail,
					'msg'=>'此数据不存在'
				];
			} 
			
		}else{
			$result = $type->add_item($banner_name,$banner_type_id,$jump_url,$banner_img_id);
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
	}
	/**
	  * 方法说明 - banner列表
	  * @url /api/v2/cms/banner_list_get
	  * @param {string} banner_type_id banner类别
	  * @method get
	  */
	public function banner_list_get($banner_type_id){
		$list = new CmsBannerItemModel();
		$result = $list->banner_list_get($banner_type_id);
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
	/*
	*	@param：banner上传 - 返回图片id
	*	@method:post
	*	@url /api/v2/cms/add_banner_img
	*/
	public function add_banner_img(){
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
	  * 方法说明 - banner删除
	  * @url /api/v2/cms/banner_item_delete
	  * @param {int} id
	  * @method delete
	  */
	public function banner_item_delete($id){
		(new IDMustBePositiveInt())->goCheck();
		$delete = CmsBannerItemModel::destroy($id);
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