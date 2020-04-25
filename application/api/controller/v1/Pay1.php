<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/8/22
 * Time: 9:38
 */
/*此方法最后回调未走通*/
namespace app\api\controller\v1;
use app\api\controller\BaseController;
use app\api\service\WxNotify;
use app\api\validate\IDMustBePositiveInt;
use app\api\service\Pay as PayService;
use think\Log;

class Pay extends BaseController
{
    protected $beforeActionList = [
        'checkExclusiveScope'=>['only'=>'placeOrder']
    ];

    public function getPreOrder($id='')
    {

        (new IDMustBePositiveInt())->goCheck();
        $pay = new PayService($id);
        return $pay->pay();

    }

//转发到这里
    public function redirectNotify()
    {
        //t通知频率15/15/30/180/1800/1800/1800/1800/3600;单位：s
        //1.j检测库存量
        //2.更新订单status状态
        //3.减去库存
        //4.返回结果
        //te特点：post,xml格式，不会携带查询参数
        $notify =  new WxNotify();
        $config = new \WxPayConfig();
        $notify->Handle($config,true);
    }



    public function receiveNotify()
    {



//        $xmlData = file_get_contents('php://input');
//        $result = curl_post_raw('http://wechat.siemensgabor.com/zerg/public/index.php/api/v1/pay/re_notify?XDEBUG_SESSION_START=10050',
//            $xmlData);
//        return $result;

        $notify =  new WxNotify();
        $config = new \WxPayConfig();
        RETURN $notify->Handle($config,false);


    }
}