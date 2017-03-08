<?php
/**
 * Created by PhpStorm.
 * 公共控制器
 * User: 帅
 * Date: 2017/3/1
 * Time: 21:00
 */

namespace Home\Controller;


use Think\Controller;
use Think\Upload;

class CommonController extends EmptyController
{
    //分页数据方法
    protected function page($page = 0, $limit = 1)
    {
        //$limit:取出记录数;$page:页数;
        $limit = !empty($limit) ? $limit : 1;
        $page = !empty($page) ? (($page-1) * $limit) : 0;
        return ['page'=>$page,'limit'=>$limit];    //返回数组
    }
}