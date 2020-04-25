<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/6/30
 * Time: 18:06
 */

namespace app\api\controller\v1\cms;
use think\Request;
use app\api\validate\IDMustBePositiveInt;
use app\api\model\cms\CmsUsernews as UsernewsModel;
use app\api\model\ThirdApp as UserModel;
use app\api\model\Image as ImageModel;
use app\api\validate\PagingParameter;
use app\api\model\cms\CmsUsernewsImage as UsernewsImageModel;
use app\api\model\cms\CmsBelong as BelongModel;
// use traits\model\SoftDelete;
// use app\lib\exception\BannerMissException;

class CmsUsernews
{
       /*
    #cms
    get
    @url /cms/delete
    删除
    */ 
    
    public function delete($id){
        header('Access-Control-Allow-Origin: *');
        header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");
        header('Access-Control-Allow-Methods: PUT,POST,GET,DELETE,OPTIONS');
        $delete = UsernewsModel::destroy($id);
        
        return $delete;
    }

    /*
    #cms
    post
    @url /cms/content
    z增加详情页
    */ 
    public function addContent(){
        header('Access-Control-Allow-Origin: *');
        header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");
        header('Access-Control-Allow-Methods: PUT,POST,GET,DELETE,OPTIONS');
        $files = request()->file('file');
        $index = 0;
        // return $files;
        foreach($files as $file){
            // 移动到框架应用根目录/public/uploads/ 目录下
            $info = $file->move(ROOT_PATH . 'public' . DS . 'uploads');
            $path = ROOT_PATH . 'public' . DS . 'uploads';
            if($info){
                $index++;
                $ImageModel = new  ImageModel();
                $img = $ImageModel->addImg("/".$info->getSaveName(),$form=1);
                $data[]=$img['url'];
                $id[]=$img['id'];
                $errno=0;
            }else{
                // 上传失败获取错误信息
                // echo $file->getError();
                $errno=$file->getError();
            }    
        };
        return [
            "errno"=>$errno,
            'id'=>$id,
            'data' => $data
        ];

    }

    public function addUsernews(){
        header('Access-Control-Allow-Origin: *');
        header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");
        header('Access-Control-Allow-Methods: GET');
       

        
        if(request()->isPost()){
            $input=input('post.');
            $username = $input['name'];
            $phone = $input['phone'];
            $price = $input['price'];
            $discount = $input['discount'];
            $inputman = $input['inputname'];
            $guider = $input['guider'];
            $belong = $input['belong'];
            $contract = $input['contract'];
            $buy = $input['buy'];
            $function = $input['function'];
            $gifts = $input['gifts'];
            $follow = $input['follow'];
            $remarks = $input['remarks'];
            $where =  $input['where'];
            $local =  $input['local'];
            $use =  $input['use'];
            $tags = '未审核';
            $index=0;
            $imgUrl=[];
            if($input['follow']=='已订单'){
                $contract_time = time();
            }else{
                $contract_time = "";
            }
            
            
            $user = UsernewsModel::get(['phone' => $phone]);
            // return $user;
            if($user){
                return [
                    'errno'=>1,
                    'result'=>$user
                ];
            }
             // 获取表单上传文件
            $files = request()->file('file');
            
            if($files!=""){
                foreach($files as $file){
                    // 移动到框架应用根目录/public/uploads/ 目录下
                    $info = $file->move(ROOT_PATH . 'public' . DS . 'uploads');
                    $path = ROOT_PATH . 'public' . DS . 'uploads';
                    if($info){
                        $index++;
                        $ImageModel = new  ImageModel();
                        $img= $ImageModel->addImg("\\".$info->getSaveName(),$form=1);
                        $id[]=$img['id'];
                    }else{
                        // 上传失败获取错误信息
                        // echo $file->getError();
                        $data[]=$file->getError();
                    }    
				}
                     
                    }else{
                      
                    }
                    $UsernewsModel = new UsernewsModel();
                    $UsernewsModel->username = $username;
                    $UsernewsModel->use = $use;
                    $UsernewsModel->phone = $phone;
                    $UsernewsModel->price = $price;
                    $UsernewsModel->discount = $discount;
                    $UsernewsModel->inputman = $inputman;
                    $UsernewsModel->guider = $guider;
                    $UsernewsModel->belong = $belong;
                    $UsernewsModel->buy = $buy;
                    $UsernewsModel->function = $function;
                    $UsernewsModel->gifts = $gifts;
                    $UsernewsModel->follow = $follow;
                    $UsernewsModel->remarks = $remarks;
                    $UsernewsModel->where = $where;
                    $UsernewsModel->tags = $tags;
                    $UsernewsModel->local = $local;
                    $UsernewsModel->contract = $contract;
                    $UsernewsModel->contract_time = $contract_time;
                    $UsernewsModel->save();
                    $UsernewsID = $UsernewsModel->id;
                    $usernews_image_Model = new UsernewsImageModel();
                    if($files!=""){
                        for($i = 0;$i<count($id);$i++){
                    
                            $list[] = ['img_id'=>$id[$i],'usernews_id'=>$UsernewsID,'order'=>$i];
                        };
                        $result = $usernews_image_Model->addImgs($list);
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
    @url /cms/getPesonAll
    普通管理员查找个人填写的所有信息
    */ 
    public function getPesonAll($page=1,$size=15,$inputer){
        header('Access-Control-Allow-Origin: *');
        header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");
        header('Access-Control-Allow-Methods: GET');
        (new PagingParameter())->goCheck();
        $user = BelongModel::get(['belongname' => $inputer]);
        $tid = $user->id;
        $news=UsernewsModel::getAllNewsByPerson($page,$size,$inputer,$tid);
        $count = UsernewsModel::where('inputman','=',$inputer)->whereOr('belong','=',$tid)->order('id desc')->count();
        if(!$count){
            $count = "0";
        }
        if ($news->isEmpty())
        {
            return [
                'count'=>"",
                'current_page' => $news->getCurrentPage(),
                'inputman'=>[$inputer],
                'data' => []
            ];
        }
        $data = $news->hidden([ 'update_time'])
            ->toArray();
        return [
            'count'=> $count,
            'current_page' => $news->getCurrentPage(),
            'inputman'=>[$inputer],
            'data' => $data
        ];
    }



    
     /*
    #cms
    @url /cms/getPesonAll
    普通管理员查找个人填写的所有信息
    */ 
    public function getLeaderAll($page=1,$size=15,$inputer){
        header('Access-Control-Allow-Origin: *');
        header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");
        header('Access-Control-Allow-Methods: GET');
        (new PagingParameter())->goCheck();

        $user = UserModel::get(['nick_name' => $inputer]);
        $tid = $user->id;
        $kefus = UserModel::where('tid',$tid)->column('nick_name');
        $kefus[] = $inputer;
        
        $belong = BelongModel::get(['belongname' => $inputer]);
        $belongid = $user->id;
        
        $usersid = BelongModel::where('belongname','in',$kefus)->column('id');
        
       
        $news=UsernewsModel::getLeaderAll($page,$size,$kefus,$usersid);
        $count = UsernewsModel::where('inputman','in',$kefus)->whereOr('belong','in',$usersid)->order('id desc')->count();
        
        if(!$count){
            $count = "0";
        }
        if ($news->isEmpty())
        {
            return [
                'count'=>"",
                'current_page' => $news->getCurrentPage(),
                'inputman'=>$kefus,
                'data' => []
            ];
        }
        $data = $news->hidden([ 'update_time'])
            ->toArray();
        return [
            'count'=> $count,
            'current_page' => $news->getCurrentPage(),
            'inputman'=>$kefus,
            'data' => $data
        ];
    }
     /*
    #cms
    @url /cms/getAll
    普通审查员以上查找所有信息
    */ 
    public function getAll($page=1,$size=15){
        header('Access-Control-Allow-Origin: *');
        header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");
        header('Access-Control-Allow-Methods: GET');
        (new PagingParameter())->goCheck();
        $news=UsernewsModel::getAllNewsBycheckedman($page,$size);
        
        $kefus = UserModel::column('nick_name');
        
        $count = UsernewsModel::order('id desc')->count();
        if ($news->isEmpty())
        {
            return [
                'count'=>"",
                'current_page' => $news->getCurrentPage(),
                'inputman'=>$kefus,
                'data' => []
            ];
        }
        $data = $news->hidden([ 'update_time'])
            ->toArray();
        return [
            'count'=> $count,
            'current_page' => $news->getCurrentPage(),
            'inputman'=>$kefus,
            'data' => $data
        ];
    }

    /**
     * @url /cms/searchUsernews
     * @get
     */
    public static function checkget($input){
        $map = [];
        if(array_key_exists('local', $input)){
            if($input['local']!=''){
                $map['local'] = ['like','%'.$input['local'].'%'];
            }
            
        } else {
            // $map[] = "";
        }
        if(array_key_exists('inputman', $input)){
            // $inputman = $input['inputman'];
            if($input['inputman']!=''){
                $map['inputman'] = $input['inputman'];
                $belong = BelongModel::get(['belongname' => $input['inputman']]);
                $belongid = $belong['id'];
                $map['belong'] = $belongid;
            }
            
        } else {
        }
        
        if(array_key_exists('phone', $input)){
            if($input['phone']!=''){
                $map['phone'] = $input['phone'];
            }
           
        } else {
            
        }

        if(array_key_exists('startTime', $input)){
            if(array_key_exists('follow', $input)){
                if($input['follow']=='已订单'){
                    if($input['startTime']!=''){
                        $map['contract_time'] =['between',[$input['startTime'],$input['endTime']]] ;
                    }
                }else{
                    if($input['startTime']!=''){
                        $map['create_time'] =['between',[$input['startTime'],$input['endTime']]] ;
                    }
                }
            }

            
            
        } 

        if(array_key_exists('follow', $input)){
            if($input['follow']!=''){
                $map['follow'] = $input['follow'];
            }
           
        } 
        
        return $map;
    }
    public function searchUsernews($page=1,$size=15){
        header('Access-Control-Allow-Origin: *');
        header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");
        header('Access-Control-Allow-Methods: GET');
        $input=input('get.');
         $news = self::checkget($input);
        
        //  return $news;

        $search =  UsernewsModel::searchUsernews($page,$size,$news);
        $count = UsernewsModel::where($news)->count();
        if ($search->isEmpty())
        {
            return [
                'count'=>"",
                'current_page' => $search->getCurrentPage(),
                
                'data' => []
            ];
        }
        $data = $search->hidden(['create_time', 'update_time'])
            ->toArray();
        return [
            'count'=> $count,
            'current_page' => $search->getCurrentPage(),
            
            'data' => $data
        ];
    }
    /*
    @url /cms/checkedman
    @post
    审查员确认礼品情况
    */ 
    public function checkedman(){
        header('Access-Control-Allow-Origin: *');
        header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");
        header('Access-Control-Allow-Methods: GET');

        $input=input('get.');
        // return $input;
        $id = $input['id'];
        $checked = $input['tags'];
        $UsernewsModel = new UsernewsModel();
        if($UsernewsModel->save([
            'tags'  =>  $checked
        ],['id' =>$id])){
            return true;
        }
    }

    /**
     * 获取某订单详细数据，用于修改
     * /cms/getchange
     * @get
     */
    public function getChange ($id){
        header('Access-Control-Allow-Origin: *');
        header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");
        header('Access-Control-Allow-Methods: GET');
        (new IDMustBePositiveInt())->goCheck();
        $detail = UsernewsModel::getUsernewsDetail($id);
        return $detail;
        // if(!$product){
        //     throw new ProductException();
        // }
        // return $product;
    }

    /**
     * 修改某订单数据
     * /cms/change
     * @post
     */
    public function change(){
        header('Access-Control-Allow-Origin: *');
        header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");
        header('Access-Control-Allow-Methods: GET');
        
        if(request()->isPost()){
            $input=input('post.');
            $id = $input['id'];
            $username = $input['name'];
            $phone = $input['phone'];
            $price = $input['price'];
            $discount = $input['discount'];
            $guider = $input['guider'];
            $local = $input['local'];
            $contract = $input['contract'];
            $belong = $input['belong'];
            $buy = $input['buy'];
            $gifts = $input['gifts'];
            $follow = $input['follow'];
            $remarks = $input['remarks'];
            $order = $input['order'];
            $order ++;
            $index=0;
            $imgUrl=[];
            if($input['follow']=='已订单'){
                $contract_time = time();
            }else{
                $contract_time = "";
            }
            
            //根据ID 读取之前数据，目的：为了拼接富文本和订单图片，让两者无法修改
            $detail = UsernewsModel::getUsernewsDetail($id);
            $remarkstotal =$detail->remarks.'<br>'.date('Y-m-d H:i:s',time()).'<br>'.$remarks;
            
             // 获取表单上传文件
            $files = request()->file('file');
            
            if($files!=""){
                foreach($files as $file){
                    // 移动到框架应用根目录/public/uploads/ 目录下
                    $info = $file->move(ROOT_PATH . 'public' . DS . 'uploads');
                    $path = ROOT_PATH . 'public' . DS . 'uploads';
                    if($info){
                        $index++;
                        $ImageModel = new  ImageModel();
                        $img= $ImageModel->addImg("\\".$info->getSaveName(),$form=1);
                        $ids[]=$img['id'];
                       
                      
                    }else{
                        // 上传失败获取错误信息
                        // echo $file->getError();
                        $data[]=$file->getError();
                    }    
                        }
                       
                    }else{
                      
                    }
                    $UsernewsModel = new UsernewsModel();
                    $UsernewsModel->save([
                        'username'  => $username,
                        'phone' => $phone,
                        'price' => $price,
                        'discount' => $discount,
                        'guider' => $guider,
                        'local' => $local,
                        'contract' => $contract,
                        'contract_time' => $contract_time,
                        'belong' => $belong,
                        'buy' => $buy,
                        'gifts' => $gifts,
                        'follow' => $follow,
                        'remarks' => $remarkstotal
                    ],['id' => $id]);
                    
                    $UsernewsID = $id;
                    $usernews_image_Model = new UsernewsImageModel();
                    if($files!=""){
                        for($i = 0;$i<count($ids);$i++){
                            $orders = $i+$order;
                            $list[] = ['img_id'=>$ids[$i],'usernews_id'=>$UsernewsID,'order'=>$orders];
                        };
                        
                        $result = $usernews_image_Model->addImgs($list);
                    }else{
                        $result = false;
                    }
                    if($UsernewsModel){
                        return [
                            'errno'=>0,
                            'imgResult'=>$result,
                            'usernewsResult'=>$UsernewsModel,
                        ];
                    }else{
                        return [
                            'errno'=>1000,
                            'imgResult'=>$result,
                            'usernewsResult'=>$UsernewsModel,
                        ];
                    };
                }        
        }
}