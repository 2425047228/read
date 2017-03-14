<?php
/**
   书城控制器
   王治家
   17.3.8
 */

namespace Home\Controller;


class BookCityController extends CommonController
{
	public function bookcity(){
		$list = M("category") -> limit(3) -> select();
		$this -> assign("list",$list);
		$this -> display();
	}

}