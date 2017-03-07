<?php
namespace Home\Controller;


class IndexController extends CommonController
{
    public function index()
    {
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