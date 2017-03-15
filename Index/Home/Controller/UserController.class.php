<?php
/**
   个人中心控制器
   王治家
   17.3.8
 */

namespace Home\Controller;


class UserController extends CommonController
{
	private function fenxiang(){
		
		
		$fenxiang['title'] = "芝麻阅读，免费好书，精彩不断！";
		$fenxiang['desc'] = "书已经帮您选好了，我们在芝麻阅读等您......";
		$fenxiang['img'] = "http://".$_SERVER['HTTP_HOST']."/Public/Index/images/share.png";
		$fenxiang['url'] = $_SERVER['HTTP_HOST'].U("User/login");
		return $fenxiang;
	}
	
	
	public function login(){
		$this -> display();
	}
	
	
	public function user(){
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
                        cookie('userId', $addinfo, $cookieTime);
                    }
                }catch (\Exception $e) {}
            } else {
                cookie('userId', $userFind['id'], $cookieTime);
            }
            cookie('openid',$userInfo['openid'],$cookieTime);
            cookie('avatarFile', $userInfo['headimgurl'], $cookieTime);
        }
	}
	
	
	//个人中心 展示页面
	public function me(){
		
		$share = $this -> share();
		$this -> assign("signPackage",$share);
		$fenxiang = $this -> fenxiang();
		$this -> assign("fenxiang",$fenxiang);
		
		$this -> user();
		$userid = cookie("userId");
		if(!$userid){
			$this -> redirect("login");
		}
		$data = M("user") -> where(["id"=>$userid]) -> find();
		$data["hobby"] = M("user_hobbys") -> where(['user_id'=>$userid]) -> field("category_id") -> select();
		foreach($data['hobby'] as $k=>$v){
			$data['hobby'][$k]['hobby_name'] = M("category") -> where(['id'=>$v['category_id']]) -> getField("category");
		}
		$this -> assign("data",$data);
		$this -> display();
	}
	
	public function personalinformation(){
		$userid = cookie("userId");
		
		//用户基本信息
		$data = M("user") -> where(["id"=>$userid]) -> find();
		$this -> assign("data",$data);
		
		//用户兴趣
		$hobby_list = M("category") -> select();
		
		
		//查出用户原本有的兴趣，用于前端样式
		$hobby = M("user_hobbys") -> where(['user_id'=>$userid]) -> field("category_id") -> select();
		foreach($hobby as $k=>$v){
			foreach($hobby_list as $kk=>$vv){
				if($hobby_list[$kk]['state'] == 0){
					if($v['category_id'] == $vv['id']){
						$hobby_list[$kk]['state'] = 1;
					}else{
						$hobby_list[$kk]['state'] = 0;
					}
				}
			}
			
		}
		$this -> assign("hobby_list",$hobby_list);
		$this -> display();
	}
	
	public function userinfo_mod(){
		$userid = cookie("userId");
		$data = I("post.userinfo");
		$userinfo['nick_name'] = $data[0]['nick_name'];
		$userinfo['sex'] = $data[1]['sex'];
		$userinfo['mobile_number'] = $data[2]['mobile_number'];
		$aa = M("user") -> where(['id'=>$userid]) -> save($userinfo);
		$hobbys = I("post.hobbys");
		M("user_hobbys") -> where(["user_id"=>$userid]) -> delete();
		foreach($hobbys as $k=>$v){
			$hobbys[$k]['user_id'] = $userid;
		}
		$bb = M("user_hobbys") -> addAll($hobbys);
		
		if($aa || $bb){
			echo "1";  //有所修改
		}else{
			echo "0";   //没有任何修改
		}
	}
	
	//反馈
	public function feedback(){
		$table = M("back");
		if($_POST){
			$data = I("post.");
			$data['u_id'] = cookie("userId");
			$data['time'] = time();
			$aa = $table -> add($data);
			if($aa){
				echo "1";   //数据提交成功
			}else{
				echo "0";   //数据提交失败
			}
		}else{
			$this -> display();
		}
	}
	
	//获取短信验证码
	public function yanzheng(){
		//$mobile = I("post.mobile");
		//$mob = I("post.mob");
		$apikey = "9b70d20995000236663c521a092ac776"; //修改为您的apikey(https://www.yunpian.com)登陆官网后获取
		//$mobile = $mobile; //请用自己的手机号代替
		// if($mobile != $mob){
			// echo "手机号不正确";
			// exit;
		// }
		$chars = "0123456789";
		$str = "";
		for ($i = 0; $i < 4; $i++) {
			$str .= substr($chars, mt_rand(0, strlen($chars) - 1), 1);
		}
		
		 echo $str;
		 exit;

		$text="【诗奈尔洗护】您的验证码是".$str;
		
		
		$data=array('text'=>$text,'apikey'=>$apikey,'mobile'=>$mobile);
		$url = "https://sms.yunpian.com/v1/sms/send.json";
		
		//$tijiao = new \Org\Weixin\Tijiao;  //curl 数据提交
		$array = $this -> send($url,$data);
		if($array['code'] == 0 && $array['msg'] == "OK"){
			echo $str;
		}else{
			echo "-1";
		}
		
	}
	

}