<?php
/**
   美文控制器
   王治家
   17.3.8
 */

namespace Home\Controller;


class BeautifulWordsController extends CommonController
{
	private function fenxiang(){
		
		
		$fenxiang['title'] = "芝麻阅读，免费好书，精彩不断！";
		$fenxiang['desc'] = "每日更新精选美文，日日更新日日新，充分利用起你的碎片时间。";
		$fenxiang['img'] = "http://".$_SERVER['HTTP_HOST']."/Public/Index/images/share.png";
		$fenxiang['url'] = $_SERVER['HTTP_HOST'].U("beautifulwords");
		return $fenxiang;
	}
	
	//美文列表
	public function beautifulwords(){
		
		$share = $this -> share();
		$this -> assign("signPackage",$share);
		$fenxiang = $this -> fenxiang();
		$this -> assign("fenxiang",$fenxiang);
		
		$table = M("beautiful_words");
		$list = $table -> order("id DESC") -> limit(4) -> select();
		$this -> assign("list",$list);
		$this -> display();
	}
	
	//美文详情页
	public function detail(){
		$table = M("beautiful_words");
		$id = I("get.id");
		$table -> where(['id'=>$id]) -> setInc("see",1);
		$data = $table -> where("id = ".$id) -> find();
		$data['content'] = htmlspecialchars_decode($data['content']);
		$this -> assign("data",$data);
		
		//dump($data);
		//分享信息
		$share = $this -> share();
		$this -> assign("signPackage",$share);
		
		$fenxiang['title'] = $data['title'];
		$fenxiang['desc'] = "看更多精彩美文，读更多经典好书，尽在芝麻阅读。";
		$fenxiang['img'] = "http://".$_SERVER['HTTP_HOST']."/Public/Index/images/share.png";
		$fenxiang['url'] = $_SERVER['HTTP_HOST'].U("detail",array("id"=>$id));
		$this -> assign("fenxiang",$fenxiang);
		$this -> display();
	}
	
	//ajax 美文搜索
	public function search(){
		$table = M("beautiful_words");
		$data = I("post.search");
		$where['author'] = array("like",'%'.$data.'%');
		$where['title'] = array("like",'%'.$data.'%');
		$where['_logic'] = 'or';
		$list = $table -> where($where) -> select();
		$this -> assign("list",$list);
		$this -> display();
	}
	
	//下拉添加数据
	public function xiala(){
		$table = M("beautiful_words");
		$number = I("post.number");
		$page = $this->page($number,4);
		//$small = $number*4;    ['page'=>$page,'limit'=>$limit]
		$list = $table -> order("id DESC") -> limit($page['page'],$page['limit']) -> select();
		foreach($list as $k=>$v){
			$list[$k]['sent_time'] = date("Y.m.d",$v['sent_time']);
		}
		$this -> ajaxReturn($list);
	}
	
	//音乐播放数量加1
	public function music(){
		$id = I("post.id");
		M("beautiful_words") -> where(["id"=>$id]) -> setInc("listen");
	}
	
	//点赞
	public function praise(){
		$id = I("post.id");
		$state = I("post.state");
		if($state == 1){
			M("beautiful_words") -> where(["id"=>$id]) -> setInc("praise");
		}else{
			M("beautiful_words") -> where(["id"=>$id]) -> setDec("praise");
		}
	}
	

}