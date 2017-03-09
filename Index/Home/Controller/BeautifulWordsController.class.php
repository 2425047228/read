<?php
/**
   美文控制器
   王治家
   17.3.8
 */

namespace Home\Controller;


class BeautifulWordsController extends CommonController
{
	
	//美文列表
	public function beautifulwords(){
		$table = M("beautiful_words");
		$list = $table -> order("id DESC") -> limit(4) -> select();
		$this -> assign("list",$list);
		$this -> display();
	}
	
	//美文详情页
	public function detail(){
		$table = M("beautiful_words");
		$id = I("get.id");
		dump($id);
	}
	
	//ajax 美文搜索
	public function search(){
		$table = M("beautiful_words");
		$data = I("post.");
		dump($data);
		$where['author'] = array("like",'%'.I('post.').'%');
	}
	

}