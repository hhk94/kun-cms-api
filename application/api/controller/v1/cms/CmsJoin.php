<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/6/30
 * Time: 18:06
 */

namespace app\api\controller\v1\cms;
use think\Request;

use app\api\model\cms\CmsJoin as JoinModel;
use app\api\validate\PagingParameter;


class CmsJoin
{
    /**
     * /cms/joinAdd
     * get
     * 
     */
    public function add ($username,$phone,$message,$description){
        header('Access-Control-Allow-Origin: *');
        header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");
        header('Access-Control-Allow-Methods: PUT,POST,GET,DELETE,OPTIONS');
        
        $user = JoinModel::get(['phone' => $phone]);
           
        if($user){
            return [
                'errno'=>1,
                'result'=>$user
            ];
        };
        $join = new JoinModel();
        $join->username = $username;
        $join->phone = $phone;
        $join->message = $message;
        $join->description = $description;
        if($join->save()){
            return [
                'errno'=>0,
                'result'=>true
            ];
        }
    }

    public function lookAll($page=1,$size=15){
        header('Access-Control-Allow-Origin: *');
        header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");
        header('Access-Control-Allow-Methods: PUT,POST,GET,DELETE,OPTIONS');
        (new PagingParameter())->goCheck();
        $news=JoinModel::getAll($page,$size);
        $count = JoinModel::order('id desc')->count();
        if ($news->isEmpty())
        {
            return [
                'count'=>"",
                'current_page' => $news->getCurrentPage(),
            
                'data' => []
            ];
        }
        $data = $news->hidden([ 'update_time'])
            ->toArray();
        return [
            'count'=> $count,
            'current_page' => $news->getCurrentPage(),
            
            'data' => $data
        ];
    }
    
}