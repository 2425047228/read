<?php
/**
 * Created by PhpStorm.
 * User: 帅
 * Date: 2017/3/1
 * Time: 21:00
 */

namespace Home\Controller;


use Think\Verify;

class LoginController extends EmptyController
{
    public function login()
    {
        //判断是否已经登陆
        if (!empty(session('validate')) && session('validate') === 'SUCCESS') {
            return $this->redirect('Index/index');
        }
        $postInfo = !empty(I('post.account')) && !empty(I('post.password')) && !empty(I('post.verify'));
        if ($postInfo) {
            $url = U('Index/index');
            cookie('account',I('post.account'));
            $Verify = new Verify();

            if ($Verify->check(I('post.verify'))) {    //判断验证码
                if (I('post.account') != C('ACCOUNT') || I('post.password') != C('PASSWORD')) {    //判断账号和密码
                    $url = U('login',array('message'=>'用户名或密码错误!'));
                } else {
                    session('validate','SUCCESS');
                }
            } else {
                $url = U('login',array('message'=>'验证码错误!'));
            }
            return header("location:{$url}");
        }
        $this->display();
    }

    //生成图片验证码方法
    public function verification_code()
    {
        ob_clean();
        $Verify = new Verify();    //创建验证码对象
        $Verify->length = 4;    //设定验证码长度
        $Verify->useCurve = false;    //去除曲线
        $Verify->entry();
    }

    //退出登陆
    public function logout()
    {
        session(null);
        return $this->redirect('Login/login');
    }
}