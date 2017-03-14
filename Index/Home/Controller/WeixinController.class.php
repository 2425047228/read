<?php

/**
微信方面一些操作
王治家
8.23

**/
namespace Home\Controller;
use Think\Controller;
class WeixinController extends Controller 
{
		//生成微信公众号自定义菜单
	public function menu()
	{
		$aa = new \Org\Weixin\jssdk(APPID,APPSECRET);
		$token = $aa -> token();
			$data = ' {
				 "button":[
				 
				  {
						"type":"view",
					    "name":"芝麻阅读",
						"url":"https://open.weixin.qq.com/connect/oauth2/authorize?appid='.APPID.'&redirect_uri=http://'.$_SERVER['HTTP_HOST'].'/index.php/Home/Index/index&response_type=code&scope=snsapi_userinfo&state=STATE#wechat_redirect"
				   },
				   
				   {
					   "type":"view",
					    "name":"书城",
						"url":"https://open.weixin.qq.com/connect/oauth2/authorize?appid='.APPID.'&redirect_uri=http://'.$_SERVER['HTTP_HOST'].'/index.php/Home/Book/library&response_type=code&scope=snsapi_userinfo&state=STATE#wechat_redirect"
				   },
				   
				 
					{
					   "type":"view",
					    "name":"个人中心",
						"url":"https://open.weixin.qq.com/connect/oauth2/authorize?appid='.APPID.'&redirect_uri=http://'.$_SERVER['HTTP_HOST'].'/index.php/Home/User/me&response_type=code&scope=snsapi_userinfo&state=STATE#wechat_redirect"
					}
				   
				   
				   ]
			 }';
			 
			 
			 $url = "https://api.weixin.qq.com/cgi-bin/menu/create?access_token=".$token;
			 $tijiao = new \Org\Weixin\Tijiao;  //curl 数据提交
			 $menu = $tijiao -> refer($url,$data);
			 $this -> ajaxReturn($menu);
			 
			 //"url":"https://open.weixin.qq.com/connect/oauth2/authorize?appid='.APPID.'&redirect_uri=http://'.$_SERVER['HTTP_HOST'].'/index.php/Home/Index/index&response_type=code&scope=snsapi_base&state=STATE#wechat_redirect"
	}
}