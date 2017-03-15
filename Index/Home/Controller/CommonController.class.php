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
    protected function page($page = 0, $limit = 10)
    {
        //$limit:取出记录数;$page:页数;
        $limit = !empty($limit) ? $limit : 10;
        $page = !empty($page) ? (($page-1) * $limit) : 0;
        return ['page'=>$page,'limit'=>$limit];    //返回数组
    }
	
	//分享
	public function share(){
		$weixin = new \Org\Weixin\jssdk(APPID,APPSECRET);
		$share = $weixin -> getSignPackage();
		//$this -> assign("signPackage",$share);
		return $share;
		// $fenxiang['title'] = "这也太准了吧，好友邀请你去趣味测试";
		// $fenxiang['desc'] = M("quiz") -> where(['id'=>$q_id]) -> getField("name");
		// $img = M("quiz") -> where(['id'=>$q_id]) -> getField("img");
		// $fenxiang['img'] = "http://".$_SERVER['HTTP_HOST']."/Wenjian".$img;
		// $fenxiang['url'] = $_SERVER['HTTP_HOST'].U("test_result",array("q_id"=>$q_id,"grade"=>$grade,"state"=>1,"number"=>$weixin_name,"type"=>1));
		// $this -> assign("fenxiang",$fenxiang);
	}
}