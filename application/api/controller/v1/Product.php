<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/7/12
 * Time: 16:28
 */

namespace app\api\controller\v1;


use app\api\validate\Count;
use app\api\model\Product as ProductModel;
use app\api\model\ProductImage as ProductImageModel;
use app\api\model\Image as ImageModel;
use app\api\validate\IDMustBePositiveInt;
use app\lib\exception\ProductException;
use app\api\validate\PagingParameter;
use app\api\service\Token as TokenService;
use think\Request;
class Product
{
   
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
    public function addProduct(){
            header('Access-Control-Allow-Origin: *');
            header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");
            header('Access-Control-Allow-Methods: GET');
            
            if(request()->isPost()){
                $input=input('post.');
                
               
                $name = $input['name'];
                $price = $input['price'];
                $stock = $input['stock'];
                $category_id = $input['type'];
                $summary = $input['summary'];
                $onShow = $input['Onshow'];

                $index=0;
                $imgUrl=[];
                 // 获取表单上传文件
                $files = request()->file('file');
                
                if($files!=""){
                    foreach($files as $file){
                        // 移动到框架应用根目录/public/uploads/ 目录下
                        $info = $file->move(ROOT_PATH . 'public' . DS . 'uploads');
                        $path = ROOT_PATH . 'public' . DS . 'uploads';
                        if($info){
                            // 成功上传后 获取上传信息
                            // 输出 jpg
                            // echo $info->getExtension(); 
                            
                            // 输出 42a79759f284b767dfcb2a0197904287.jpg
                            // echo $info->getFilename(); 
                            // $data[]=$info->getSaveName();
                            // $imgUrl=$path."\\".$info->getSaveName();
                            // array_push($imgUrl,$path."\\".$info->getSaveName());
                            // $imgUrl[$index]=$path."\\".$info->getSaveName();
                            $index++;
                            // $data[]=$info->getExtension();
                            // $data[]=$info->getFilename();
                            // $data[]=$path;
                            // $data[]=$imgUrl;
                            $ImageModel = new  ImageModel();
                            $img= $ImageModel->addImg("\\".$info->getSaveName(),$form=1);
                            $id[]=$img['id'];
                            $url[]=$img['url'];

                            $data = [
                                'id'=>$id,
                                'url'=>$url
                            ];

                            
                            
                            
                        }else{
                            // 上传失败获取错误信息
                            // echo $file->getError();
                            $data[]=$file->getError();
                        }    
                    }
                    $main_img_url = $url[0];
                    $true_url = explode("uploads",$main_img_url);
                   $imgUrl = $true_url[1];
                    $img_id = $id[0];
                }else{
                    // $productModel->main_img_url = " ";
                    $imgUrl = "";
                    $img_id = "";
                }
               
                
               
                $productModel = new ProductModel();
                $productModel->name = $name;
                $productModel->price = $price;
                $productModel->stock = $stock;
                $productModel->category_id = $category_id;
                $productModel->main_img_url = $imgUrl;
                $productModel->from = 1;
                $productModel->summary = $summary;
                $productModel->img_id = $img_id;
                $productModel->onShow = $onShow;
                $productModel->save();
                $productID = $productModel->id;
                $product_image_Model = new ProductImageModel();
                // return[
                //     'id'=>$id,
                //     'productid'=>$productID
                // ];
                if($files!=""){
                    for($i = 0;$i<count($id);$i++){
                   
                        $list[] = ['img_id'=>$id[$i],'product_id'=>$productID,'order'=>$i];
                    };
                    $result = $product_image_Model->addImgs($list);
                }else{
                    $result = 'ok';
                }
                
                if($result){
                    return [
                        'errno'=>0
                    ];
                };

                

            }

          
    }

    
    /*
     * @url /product/recent
     * */
    public function getRecent($count=14){
        (new Count())->goCheck();
        $products=ProductModel::getMostRecent($count);
        if($products->isEmpty()){
            throw new ProductException();
        }
        $products=$products->hidden(['summary']);

        return $products;
    }


    /*
     * @url /product/by_category?id=
     * */
    public function getAllInCategory($id){
        (new IDMustBePositiveInt())->goCheck();
        $products=ProductModel::getProductsByCategoryID($id);
        if($products->isEmpty()){
            throw new ProductException();
        }
        $products=$products->hidden(['summary']);

        return $products;
    }

    /*
     * @url /product/:id
     * */

    public function getOne($id){
        (new IDMustBePositiveInt())->goCheck();
        $product = ProductModel::getProductDetail($id);

        if(!$product){
            throw new ProductException();
        }
        return $product;
    }

    /*
    #cms
    @url /product/all
    */ 
    public function getAll($page=1,$size=15){
        header('Access-Control-Allow-Origin: *');
        header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");
        header('Access-Control-Allow-Methods: GET');
        (new PagingParameter())->goCheck();
        // if(TokenService::needSuperUser()){
        //     return TokenService::needSuperUser()
            
        // }
        $products=ProductModel::getAllProduct($page,$size);
        $count = ProductModel::order('id desc')->count();
        if ($products->isEmpty())
        {
            return [
                'count'=>"",
                'current_page' => $products->getCurrentPage(),
                'data' => []
            ];
        }

        $data = $products->hidden(['create_time', 'update_time'])
            ->toArray();
        return [
            'count'=> $count,
            'current_page' => $products->getCurrentPage(),
            'data' => $data
        ];

    }

    
    

}