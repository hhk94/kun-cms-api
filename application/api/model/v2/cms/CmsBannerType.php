<?php 
/**
  * Created by hkun
  * Email: 350839123@qq.com
  * Date: 2020-04-13
  */

namespace app\api\model\v2\cms;
use app\api\model\BaseModel;
use traits\model\SoftDelete;

class CmsBannerType extends BaseModel {
	use SoftDelete;
	protected $deleteTime = 'delete_time';
	protected $hidden=['delete_time','update_time','create_time'];
	protected $autoWriteTimestamp = true;
	// public function article_item(){
	//     return $this->hasMany('CmsArticle','article_type_id','id');
	// }
	
	public function add_type($typename)
	{
	    $this->typename = $typename;
		if($this->save()){
			return $this;
		}else{
			return false;
		}
	}
	
	public function banner_type_list_get(){
		$article_type = self::order('id')
		->select();
		return $article_type;
	}
}
?>