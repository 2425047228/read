<?php
/**
   个人中心控制器
   王治家
   17.3.8
 */

namespace Home\Controller;


class UserController extends CommonController
{
	public function me(){
		$this -> display();
	}
	
	public function userinfo(){
		
		$userinfo = weixinuserinfo();
		dump($userinfo);
		
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
	

}