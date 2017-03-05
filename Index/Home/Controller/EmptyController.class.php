<?php
/**
 * Created by PhpStorm.
 * User: 帅
 * Date: 2017/3/1
 * Time: 21:01
 */

namespace Home\Controller;


use Think\Controller;

class EmptyController extends Controller
{
    public function _empty()
    {
        return $this->redirect('Index/index');
    }
}