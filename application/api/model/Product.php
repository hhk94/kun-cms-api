<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/7/11
 * Time: 9:46
 */

namespace app\api\model;


class Product extends BaseModel
{
    protected $hidden=[
        'delete_time','create_time','update_time','pivot','from','category_id','main_img_id'
    ];
    protected $autoWriteTimestamp = true;

    public function getMainImgUrlAttr($value,$data){

        return $this->prefixImgUrl($value,$data);
    }

    public function imgs(){
        return $this->hasMany('ProductImage','product_id','id');
    }

    public function properties(){
        return $this->hasMany('ProductProperty','product_id','id');
    }

    public function addProduct(){

    }

    public static function getMostRecent($count){
        $products=self::limit($count)->order('create_time desc')->select();
        return $products;
    }

    public static function getAllProduct($page=1,$size=15){
        $products = self::with([
            'imgs'=>function($query){
                $query->with(['imgUrl'])->order('order','asc');
            }
        ])->order('create_time desc')
            ->paginate($size, true, ['page' => $page]);
        return $products ;
    }

    public static function getProductsByCategoryID($categoryID){
        $products=self::where('category_id','=',$categoryID)->select();
        return $products;
    }

    public static function getProductDetail($id){
        $product=self::with([
            'imgs'=>function($query){
                $query->with(['imgUrl'])->order('order','asc');
            }
        ])->with(['properties'])->find($id);
        return $product;
    }
}