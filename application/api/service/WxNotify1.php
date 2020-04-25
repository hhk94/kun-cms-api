<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/8/25
 * Time: 14:52
 */
/*此方法最后回调未走通*/
namespace app\api\service;
use app\api\model\Order as OrderModel;
use app\api\service\Order as OrderService;
use app\lib\enum\OrderStatusEnum;
use app\api\model\Product;
use think\Log;
use think\Db;
use think\Loader;

Loader::import('WxPay.WxPay',EXTEND_PATH,'.Api.php');


class WxNotify extends \WxPayNotify
{


    public function NotifyProcess($objData, $config, &$msg)
    {
//        public function NotifyProcess($objData,  &$msg)
//    {

        if($objData['result_code'] == 'SUCCESS')
        {
            $orderNO= $objData['out_trade_no'];
            Db::startTrans();
            try
            {

                $order  =OrderModel::where('order_no','=',$orderNO)->lock(true)->find();
                if ($order->status == 1) {
                    $service = new OrderService();
                    $status = $service->checkOrderStock($order->id);
                    if ($status['pass']) {
                        $this->updateOrderStatus($order->id, true);
                        $this->reduceStock($status);
                    } else {
                        $this->updateOrderStatus($order->id, false);
                    }
                }
                Db:commit();


            }
            catch(Exception $ex)
            {
                Db::rollback();
                Log::error($ex);
                return false;
            }
        }else{
            return true;
        }
    }

    private function updateOrderStatus($orderID,$success)
    {
        $stockStatus = $success?OrderStatusEnum::PAID : OrderStatusEnum::PAID_BUT_OUT_OF;
        OrderModel::where('id','=',$orderID)->update(['status'=>$stockStatus]);
    }

    private function reduceStock($stockStatus)
    {
        foreach ($stockStatus['pStatusArray'] as $singlePStatus) {
            Product::where('id','=',$singlePStatus['id'])->setDec('stock',$singlePStatus['count']);
        }
    }
}