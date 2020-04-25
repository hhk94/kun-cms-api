<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/7/15
 * Time: 16:53
 */

namespace app\api\controller\v1;
use app\api\model\Category as CategoryModel;
use app\lib\exception\CategoryException;
use app\api\model\Image as ImageModel;

class Category
{

    /*
     * @url  /category/all
     * */

    public function getAllCategories(){

        header('Access-Control-Allow-Origin: *');
        header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");
        header('Access-Control-Allow-Methods: PUT,POST,GET,DELETE,OPTIONS');

        $categories= CategoryModel::getCategory();


        if($categories->isEmpty()){
            throw new CategoryException();
        }
        return $categories->toArray();
    }

     /*
     * @url  /category/all
     * */
    public function addType($typename){
        header('Access-Control-Allow-Origin: *');
        header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");
        header('Access-Control-Allow-Methods: PUT,POST,GET,DELETE,OPTIONS');
        
        // 获取表单上传文件
        $files = request()->file('file');
        if($files!=""){
            foreach($files as $file){
                // 移动到框架应用根目录/public/uploads/ 目录下
                $info = $file->move(ROOT_PATH . 'public' . DS . 'uploads');
                $path = ROOT_PATH . 'public' . DS . 'uploads';
                if($info){
                    
                    $ImageModel = new  ImageModel();
                    $img= $ImageModel->addImg("\\".$info->getSaveName(),$form=1);
                    $id=$img['id'];
                }else{
                    // 上传失败获取错误信息
                    // echo $file->getError();
                    $data[]=$file->getError();
                }    
                    }
                 
                }else{
                  
                }


        $News = new CategoryModel();
        $News->name =$typename;
        $News->topic_img_id =$id;
        if($News->save()){
            return true;
        }
        
    }
}