<?php
 
namespace app\api;
 
use think\Response;
 
class CORS
{
    public function appInit(&$params)
    {
        header('Access-Control-Allow-Origin: *');
        header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept,TOKEN");
        header('Access-Control-Allow-Methods: GET,POST,DELETE,PUT,OPTIONS');
        if (request()->isOptions()) {
            exit();
        }
    }
}
