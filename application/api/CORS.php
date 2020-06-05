<?php
 
namespace app\api;
 
use think\Response;
 
class CORS
{
    public function appInit(&$params)
    {
        header('Access-Control-Allow-Origin: *');
		 header('Access-Control-Allow-Credentials: true');
        header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept,TOKEN");
        header('Access-Control-Allow-Methods: GET,POST,DELETE,PUT,OPTIONS');
        if (request()->isOptions()) {
            exit();
        }
		// if (Request::isOptions()) { // 判断是否为OPTIONS请求
		//             exit; //因为预检请求第一次是发送OPTIONS请求返回了响应头的内容，但没有返回响应实体response body内容。这个我们不处理业务逻辑，第二次接收的get或post等才是实质的请求返回我们才处理
		//         }
    }
}
