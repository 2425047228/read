<?php
namespace Home\Controller;


use Org\Weixin\jssdk;

class IndexController extends CommonController
{
	
	private function fenxiang(){
		
		
		$fenxiang['title'] = "芝麻阅读，免费好书，精彩不断！";
		$fenxiang['desc'] = "书已经帮您选好了，我们在芝麻阅读等您......";
		$fenxiang['img'] = "http://".$_SERVER['HTTP_HOST']."/Public/Index/images/share.png";
		$fenxiang['url'] = $_SERVER['HTTP_HOST'].U("index");
		return $fenxiang;
	}
	
    public function index()
    {
		
		$share = $this -> share();
		$this -> assign("signPackage",$share);
		$fenxiang = $this -> fenxiang();
		$this -> assign("fenxiang",$fenxiang);
        if (empty(cookie('openid')) || empty(cookie('userId'))) {
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
                        cookie('userId', $addinfo);
                    }
                }catch (\Exception $e) {}
            } else {
                cookie('userId', $userFind['id']);
            }
            cookie('openid',$userInfo['openid']);
            cookie('avatarFile', $userInfo['headimgurl']);
        }

        //获取banner
        $bannerList = M('Banner')->field('banner_file, book_id')->where(['banner_state'=> 1])->select();
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