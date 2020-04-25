<?php 
/**
  * Created by hkun
  * Email: 350839123@qq.com
  * Date: 2020-04-13
  */

namespace app\api\model\v2\cms;
use app\api\model\BaseModel;
use traits\model\SoftDelete;

class CmsBannerItem extends BaseModel {
	use SoftDelete;
	protected $deleteTime = 'delete_time';
	protected $hidden=['delete_time','update_time','create_time'];
	protected $autoWriteTimestamp = true;
	public function bannerType(){
	    return $this->belongsTo('CmsBannerType','banner_type_id','id');
	}
	/**
	  * 方法说明 - 模型关联 banner-img
	  */
	public function bannerImg(){
	    return $this->hasOne('app\api\model\Image','id','banner_img_id')->bind(['banner_img_url'=> 'url']);
		
	}
	public function add_item($banner_name,$banner_type_id,$jump_url,$banner_img_id)
	{
	    $this->banner_name = $banner_name;
	    $this->banner_type_id = $banner_type_id;
	    $this->banner_img_id = $banner_img_id;
	    $this->jump_url = $jump_url;
		if($this->save()){
			return $this;
		}else{
			return false;
		}
	}
	
	public function banner_list_get($banner_type_id){
		if($banner_type_id){
			$result = self::with(['bannerType','bannerImg'])->where('banner_type_id',$banner_type_id)->order('id desc')->select()->hidden(['banner_img']);
		}else{
			$result = self::with(['bannerType','bannerImg'])->order('id desc')->select()->hidden(['banner_img']);
		}
		return $result;
	}
}
?>