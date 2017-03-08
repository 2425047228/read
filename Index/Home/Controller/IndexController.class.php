<?php
namespace Home\Controller;


use Org\Weixin\jssdk;

class IndexController extends CommonController
{
    public function index()
    {
       /* if (empty(cookie('openid'))) {
            $cookieTime = C('COOKIE_TIME');
            $userInfo = weixinuserinfo();
            $user = M('User');
            $userFind = $user->where(['openid'=>$userInfo['openid']])->find();
            if (!$userFind) {    //判断是否有该用户数据
                try{
                    $addinfo = $user->add([
                        'openid'=>$userInfo['openid'],
                        'avatar_file'=>$userInfo['headimgurl'],
                        'nick_name'=>$userInfo['nickname'],
                        'sex'=>$userInfo['sex'],
                        'register_time' => time(),
                    ]);
                    if ($addinfo) {
                        cookie('userId', $addinfo, $cookieTime);
                    }
                }catch (\Exception $e) {}
            } else {
                cookie('userId', $userFind['id'], $cookieTime);
            }
            cookie('openid',$userInfo['openid'],$cookieTime);
            cookie('avatarFile', $userInfo['headimgurl'], $cookieTime);
            cookie('sex', $userInfo['sex'], $cookieTime);
            cookie('nickName', $userInfo['nickname'], $cookieTime);
        }*/
        if (I('get.id')) {
            $cookieTime = C('COOKIE_TIME');
            $userInfo = M('User')->where(['id'=>I('get.id')])->find();
            cookie('userId', I('get.id'), $cookieTime);
            cookie('openid',$userInfo['openid'],$cookieTime);
            cookie('avatarFile', $userInfo['avatar_file'], $cookieTime);
            cookie('sex', $userInfo['sex'], $cookieTime);
            cookie('nickName', $userInfo['nick_name'], $cookieTime);
        }
        //获取banner
        $bannerList = M('Banner')->field('banner_file, book_id')->select();
        //获取热门图书列表
        $hotBookList = M('Book')->alias('b')
            ->field('b.id, b.book_banner, b.is_hot, b.book_name, b.readed_number, b.number_of_words, a.author')
            ->join('left join __AUTHOR__ a on a.id = b.author_id')
            ->where('b.is_hot > 0 AND book_state = 1')
            ->order('id desc')->select();
        $this->assign('hotBookList', $hotBookList);
        $this->assign('bannerList',$bannerList);
        $this->display();
    }

}