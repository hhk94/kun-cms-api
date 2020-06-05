<?php
/**
  * Created by hkun
  * Email: 350839123@qq.com
  * Date: 2020-04-21
  */
namespace app\api\controller\v2\cms;
use think\Request;

use app\api\model\v2\cms\CmsRouter as CmsRouterModel;
use app\lib\enum\StateEnum;

class CmsRouter {
	/**
	  * 方法说明 新增路由
	  * @url  /api/v2/cms/router_add
	  * @param {varchar} route 路由路径
	  * @param {varchar} name 路由名
	  * @param {varchar} title 路由标题
	  * @param {varchar} icon 路由图标
	  * @param {varchar} file_path 文件路径
	  * @param {int} in_nav 是否显示
	  * @param {int} level 路由等级
	  * @param {int} pre_id 上级id
	  * @method post
	  */
	public function router_add($route,$router_name,$title,$icon,$file_path,$in_nav,$level,$pre_id,$need_login){
		// return $name;
		
		if($icon==''){
			$icon = 'iconfont';
		}
		$comment = new CmsRouterModel();
		$check = $comment->where('file_path',$file_path)->whereOr('route',$route)->select();
		// return count($check);
		if(count($check)){
			return [
			    "state"=>StateEnum::fail,
			    'msg'=>'该文件路径或路由路径已经存在！'
			];
		}
		
		$result = $comment->router_add($route,$router_name,$title,$icon,$file_path,$in_nav,$level,$pre_id,$need_login);
		if($result){
			return [
			    "state"=>StateEnum::success,
			    'msg' => '新增路由成功',
			];
		}else{
			return [
			    "state"=>StateEnum::fail,
			    'msg'=>'新增路由失败'
			];
		};
	}
	/**
	  * 方法说明 获取路由列表
	  * @url  /api/v2/cms/router_list
	  * @method 
	  */
	public function router_list($type='router',$size=10,$page=1){
		$router = new CmsRouterModel();
		if($type==='router'){
			//路由模式 递归 children形式
			$result = $router->order('id desc')->where('pre_id','0')->select();
			$result = self::loop_pre($result,0);
			return [
				 "state"=>StateEnum::success,
				 "data"=>$result,
			];
		}else if($type==='select'){
			//下拉框需求模式，直接全部路由
			$result = $router->order('id desc')->select();
			return [
				 "state"=>StateEnum::success,
				 "data"=>$result,
			];
		}else if($type==='list'){
			$result = $router->router_list_pages($size,$page);
			$total = CmsRouterModel::order('id desc')->count();
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
		
		
	}

	
	public function loop_pre($arr,$id)
	{
		$list =array();
	    foreach ($arr as $k=>$v){
	        if ($v['pre_id'] == $id){
				$comment2 = new CmsRouter();
				$router = new CmsRouterModel();
				$children = $router->order('id ')->where('pre_id',$v['id'])->select();
	            $v['children'] = $comment2->loop_pre($children,$v['id']);
	            $list[] = $v;
	        }
	    }
	    return $list;
	}
}
?>