<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/6/30
 * Time: 18:06
 */

namespace app\api\controller\v1\cms;
use think\Request;
// use app\api\validate\IDMustBePositiveInt;
use app\api\model\cms\CmsActivated as ActivatedModel;
// use app\lib\exception\BannerMissException;
use app\api\model\Image as ImageModel;
use app\api\validate\PagingParameter;
use app\api\model\cms\CmsActivatedImage as ActivatedImageModel;
class CmsActivated
{

    public function addActivated(){
        header('Access-Control-Allow-Origin: *');
        header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");
        header('Access-Control-Allow-Methods: PUT,POST,GET,DELETE,OPTIONS');
        if(request()->isPost()){
            $input=input('post.');
            
            // return ini_get('upload_max_filesize')
            ;
            // return ini_get('post_max_size') ;
            // return  $_FILES;
            $url = $input['url'];
            
             // 获取表单上传文件
            $filesfile = request()->file('file');
            $filesbg = request()->file('bg');
            $filestop = request()->file('top');
            if($filestop!=""){
                foreach($filestop as $file){
                    // 移动到框架应用根目录/public/uploads/ 目录下
                    $info = $file->move(ROOT_PATH . 'public' . DS . 'uploads');
                    $path = ROOT_PATH . 'public' . DS . 'uploads';
                    if($info){
                        $ImageModel = new  ImageModel();
                        $img= $ImageModel->addImg("\\".$info->getSaveName(),$form=1);
                        $idtop=$img['id'];
                    }else{
                        // 上传失败获取错误信息
                        // echo $file->getError();
                        $data[]=$file->getError();
                    }    
                } 
            }
            if($filesbg!=""){
                foreach($filesbg as $file){
                    // 移动到框架应用根目录/public/uploads/ 目录下
                    $info = $file->move(ROOT_PATH . 'public' . DS . 'uploads');
                    $path = ROOT_PATH . 'public' . DS . 'uploads';
                    if($info){
                        $ImageModel = new  ImageModel();
                        $img= $ImageModel->addImg("\\".$info->getSaveName(),$form=1);
                        $idbg=$img['id'];
                    }else{
                        // 上传失败获取错误信息
                        // echo $file->getError();
                        $data[]=$file->getError();
                    }    
                }   
            }
            if($filesfile !=""){
                foreach($filesfile  as $file){
                    // 移动到框架应用根目录/public/uploads/ 目录下
                    $info = $file->move(ROOT_PATH . 'public' . DS . 'uploads');
                    $path = ROOT_PATH . 'public' . DS . 'uploads';
                    if($info){
                        $ImageModel = new  ImageModel();
                        $img= $ImageModel->addImg("\\".$info->getSaveName(),$form=1);
                        $idfile[]=$img['id'];
                    }else{
                        // 上传失败获取错误信息
                        // echo $file->getError();
                        $data[]=$file->getError();
                    }    
                        }
                    }  
                    $ActivatedModel = new ActivatedModel();
                    $ActivatedModel->top_id = $idtop;
                    $ActivatedModel->bg_id = $idbg;
                    $ActivatedModel->url = $url;
                    $ActivatedModel->save();
                    $ActivatedID = $ActivatedModel->id;
                    $activated_image_Model = new ActivatedImageModel();
                    if($filesfile!=""){
                        for($i = 0;$i<count($idfile);$i++){
                            $list[] = ['img_id'=>$idfile[$i],'activated_id'=>$ActivatedID,'order'=>$i];
                        };
                        $result = $activated_image_Model->addImgs($list);
                    }else{
                        $result = 'false';
                    }
                    if($result){
                        return [
                            'errno'=>0,
                            'result'=>$result
                        ];
                    };
                }        
        }

         /*
    #cms
    @url /cms/AllActivated
    普通审查员以上查找所有信息
    */ 
    public function getAll($page=1,$size=15){
        header('Access-Control-Allow-Origin: *');
        header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");
        header('Access-Control-Allow-Methods: GET');
        (new PagingParameter())->goCheck();
        $news=ActivatedModel::getAll($page,$size);
        
        
        
        $count = ActivatedModel::order('id desc')->count();
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

         /*
    #cms
    @url /cms/getOneActivated
    普通审查员以上查找所有信息
    */ 
    public function getOne($id=1){
        header('Access-Control-Allow-Origin: *');
        header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");
        header('Access-Control-Allow-Methods: GET');
        (new PagingParameter())->goCheck();
        $news=ActivatedModel::getOne($id);
        
        return $news;
       
       
    }


    
}