<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/8/22
 * Time: 9:55
 */
/*此方法最后回调未走通*/
namespace app\api\service;
use app\api\model\Order as OrderModel;
use app\api\service\Order as OrderService;
use app\lib\enum\OrderStatusEnum;
use think\Exception;
use think\Loader;
use think\Log;

//extend/WxPay/WxPay.Api.php
Loader::import('WxPay.WxPay',EXTEND_PATH,'.Api.php');

class Pay
{
    private $orderID;
    private $orderNO;

    function __construct($orderID){
        if(!$orderID){
            throw new Exception('订单号不允许为空');
        }
        $this->orderID= $orderID;
    }


    public function pay()
    {

        //订单检测
        $this->checkOrderValid();
        //进行库存量检测
        $orderService = new OrderService();
        $status = $orderService->checkOrderStock($this->orderID);
        if(!$status['pass']){
            return $status;
        }

        return $this->makeWxPreOrder($status['orderPrice']);

    }

    private function makeWxPreOrder($totalPrice)
    {
        //openid
        $openid = Token::getCurrentTokenVar('openid');
        if(!$openid){
            throw new TokenException();
        }

        $wxOrderData = new \WxPayUnifiedOrder();
        $wxOrderData->SetOut_trade_no($this->orderNO);
        $wxOrderData->SetTrade_type('JSAPI');
        $wxOrderData->SetTotal_fee($totalPrice*100);
        $wxOrderData->SetBody('嘉宝家居定制');
        $wxOrderData->SetOpenid($openid);
        $wxOrderData->SetNotify_url(config('secure.pay_back_url'));
        return $this->getPaySignature($wxOrderData);
    }

    private function getPaySignature($wxOrderData)
    {
        $config = new \WxPayConfig();

        $wxOrder = \WxPayApi::unifiedOrder($config,$wxOrderData);

        if($wxOrder['return_code'] != 'SUCCESS' || $wxOrder['result_code'] !='SUCCESS'){
            Log::record($wxOrder,'error');
            Log::record('获取预支付订单失败','error');
        }
        //perpay_id
        $this->recordPreOrder($wxOrder);

        $signature = $this->sign($wxOrder);

        return $signature;
    }


    private function sign($wxOrder)
    {
        $config = new \WxPayConfig();

        $jsApiPayData = new \WxPayJsApiPay();
        $jsApiPayData->SetAppid(config('wx.app_id'));
        $jsApiPayData->SetTimeStamp((string)time());
        $rand = md5(time().mt_rand(0,1000));
        $jsApiPayData->SetNonceStr($rand);
        $jsApiPayData->SetPackage('prepay_id='.$wxOrder['prepay_id']);
        $jsApiPayData->SetSignType('MD5');
        $sign = $jsApiPayData->SetSign($config);
        $rawValues = $jsApiPayData->GetValues();
        $rawValues['paySign'] = $sign;

//        unset($rawValues['appId']);

        return $rawValues;
    }


    private function recordPreOrder($wxOrder)
    {
        OrderModel::where('id','=',$this->orderID)->update(['prepay_id'=>$wxOrder['prepay_id']]);
    }

    private function checkOrderValid()
    {
        //订单号是否存在
        $order = OrderModel::where('id','=',$this->orderID)->find();
        if(!$order){
            throw new OrderException();
        }
        //订单号存在，却和当前用户不匹配
        if(!Token::isValidOperate($order->user_id)){
            throw new TokenExCEPTION([
                'msg'=>'订单与用户不匹配',
                'errorCode'=>10003
            ]);
        }
        //订单有可能被支付过
        if($order->status != OrderStatusEnum::UNPAID)
        {
            throw new OrderException([
                'msg'=>'订单已支付过了',
                'errorCode'=>80003,
                'code'=>400
            ]);
        }
        $this->orderNO = $order->order_no;
        return true;
    }
}