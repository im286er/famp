<?php
namespace app\api\controller;

use think\Controller;

class Api extends Controller
{
    public function initialize()
    {
        parent::initialize(); // TODO: Change the autogenerated stub
        header('Access-Control-Allow-Origin:*');
    }
}
