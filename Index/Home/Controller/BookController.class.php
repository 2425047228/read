<?php
/**
 * Created by PhpStorm.
 * User: 帅
 * Date: 2017/3/7
 * Time: 14:32
 */

namespace Home\Controller;


class BookController extends CommonController
{
    public function b_detail()
    {
        $bookId = I('get.b_id');
        if (!empty($bookId) && is_numeric($bookId)) {
            //图书信息
            $bookInfo = M('Book')->alias('b')
                ->field('b.id, b.book_cover,b.book_name,a.author,b.shelves_time,b.number_of_words,b.book_synopsis,a.author_synopsis')
                ->join('left join __AUTHOR__ a on a.id = b.author_id')
                ->where(['b.id'=>$bookId, 'b.book_state' => 1])
                ->find();
            //分类
           $categories = M('Category')->alias('c')
                ->field('c.category')
                ->join('right join __BOOK_CATEGORIES__ b on c.id = b.category_id')
                ->where(['b.book_id'=>$bookId])
                ->select();
            //是否已在书架
            $this->assign('categories', $categories);
            $this->assign('bookInfo', $bookInfo);
            $this->display();
        }
    }

    public function b_read()
    {
        if (I('post.isFirst') == 'isFirst') {    //判断是否第一次进入
            cookie('isFirst','isFirst',(3600*24*360));
            exit('SUCCESS');
        }
        $bookId = I('get.b_id');
        $validate = !empty($bookId) && is_numeric($bookId);
        if ($validate) {
            $bookInfo = M('Book')->field('book_name')->where(['id' => I('get.b_id')])->find();
            $chapter = I('get.chapter');
            if (empty($chapter) || !is_numeric($chapter)) {    //判断章节
                $chapter = 1;
            }
            $chapterObject = M('Chapter');
            $chapterInfo = $chapterObject->field('chapter, chapter_content, chapter_sort')
                ->where(['book_id'=>$bookId, 'chapter_sort'=>$chapter])
                ->find();
            $nextChapter = $chapterObject->where(['book_id'=>$bookId, 'chapter_sort'=>($chapter+1)])->getField('chapter');

            //判断来源
            $nowAction = CONTROLLER_NAME . '/' . ACTION_NAME;
            $previousAction = $_SERVER['HTTP_REFERER'];
            if (!strpos($previousAction, $nowAction)) {
                cookie('goBack', $previousAction);
            }
            $this->assign('nextChapter', $nextChapter);
            $this->assign('bookId', $bookId);
            $this->assign('chapterInfo', $chapterInfo);
            $this->assign('bookInfo', $bookInfo);
            $this->display();
        }
    }
}