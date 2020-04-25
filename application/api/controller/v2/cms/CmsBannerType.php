<?php
 /**
   * Created by hkun
   * Email: 350839123@qq.com
   * Date: 2020-04-24
   */
namespace app\api\controller\v2\cms;
use think\Request;
use app\api\model\v2\cms\CmsBannerType as CmsBannerTypeModel;
use app\lib\enum\StateEnum;
use app\api\validate\IDMustBePositiveInt;
class CmsBannerType {
	/**
	  * 方法说明 - banner类别添加
	  * @url /api/v2/cms/banenr_type_add
	  * @param {string} typename
	  * @method post
	  */
	public function banenr_type_add($id,$typename){
		$type = new CmsBannerTypeModel();
		if($id){//传入id 进入修改
			$result = $type->where('id',$id)->find();//查询数据库
			if($result){//数据存在
				$result->typename = $typename;
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
		
		
	}
	/**
	  * 方法说明 - 文章类型列表查询
	  * @url /api/v2/cms/banner_type_list_get
	  * @param null
	  * @method get
	  */
	public function banner_type_list_get(){
		$type = new CmsBannerTypeModel();
		$result = $type->banner_type_list_get();
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
	  * @url /api/v2/cms/banner_type_delete
	  * @param {int} id
	  * @method delete
	  */
	public function banner_type_delete($id){
		(new IDMustBePositiveInt())->goCheck();
		$delete = CmsBannerTypeModel::destroy($id);
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