<?php
namespace Home\Controller;
use Think\Controller;
class IndexController extends CommonController
{
    public function index()
    {
        $this->display();
    }

    //右侧框架
    public function right()
    {
        $this->display();
    }
}