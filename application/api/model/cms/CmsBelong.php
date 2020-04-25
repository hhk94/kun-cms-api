<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/7/1
 * Time: 15:18
 */

namespace app\api\model\cms;


use think\Db;
use think\Model;
use think\Exception;
use app\api\model\BaseModel;

class CmsBelong extends BaseModel
{

    protected $hidden=['delete_time','update_time','create_time','cmsid'];
    protected $autoWriteTimestamp = true;

    

    public static function getBelong($id)
        {
            
            
            if($id=='true'){
               
                $belong = self::order('id')
                ->select();
                return $belong;
            }else{
                
                $belong = self::order('id')->column('belongname');
                return $belong ;
            }
            

        }

        public function addBelong($nickname,$result){
            header('Access-Control-Allow-Origin: *');
            header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");
            header('Access-Control-Allow-Methods: PUT,POST,GET,DELETE,OPTIONS');
            
           
            $this->belongname = $nickname;
            $this->cmsid = $result;
            if($this->save()){
                return true;
            }else{
                return false;
            }
            
        }
        


}