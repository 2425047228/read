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
            $userId = cookie('userId');
            $isExistsBookshelf = M('Bookshelf')->where(['user_id' => $userId, 'book_id' => $bookId])->getField('id');
            
            $this->assign('isExistsBookshelf', $isExistsBookshelf);
            $this->assign('categories', $categories);
            $this->assign('bookInfo', $bookInfo);
            $this->display();
        }
    }

    public function b_read()
    {
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
            //判断该书是否存在于书架
            $userId = cookie('userId');
            $isExistsBookshelf = M('Bookshelf')->where(['user_id' => $userId, 'book_id' => $bookId])->getField('id');

            $this->assign('isExistsBookshelf', $isExistsBookshelf);
            $this->assign('nextChapter', $nextChapter);
            $this->assign('bookId', $bookId);
            $this->assign('chapterInfo', $chapterInfo);
            $this->assign('bookInfo', $bookInfo);
            $this->display();
        }
    }
    
    //用户喜好设置
    public function setting()
    {
        $cookieTime = C('COOKIE_TIME');
        if (I('post.isFirst') == 'isFirst') {    //判断是否第一次进入
            cookie('isFirst','isFirst',$cookieTime);
            exit('SUCCESS');
        }
        if (!empty(I('post.backgroundColor'))) {
            cookie('backgroundColor',I('post.backgroundColor'),$cookieTime);
            exit('SUCCESS');
        }
        if (!empty(I('post.fontSize'))) {
            cookie('fontSize',I('post.fontSize'),$cookieTime);
            exit('SUCCESS');
        }
        if (!empty(I('post.bookId'))) {
            $userId = $userId = cookie('userId');
            $bookId = I('post.bookId');
            $bookshelf = M('Bookshelf');
            $isExistsBookshelf = $bookshelf->where(['user_id' => $userId, 'book_id' => $bookId])->getField('id');
            if (!$isExistsBookshelf) {
                $addInfo = $bookshelf->add(['user_id'=>$userId, 'book_id'=>$bookId, 'join_time'=>time()]);
                if ($addInfo) {
                    //阅读人数递增
                    M('Book')->where(['id'=>$bookId])->setInc('readed_number', 1);
                    exit('SUCCESS');
                }
            }
            exit('FAIL');

        }
    }

    public function library()
    {
        $category = I('post.category');
        $page = I('post.page');
        $validate = strlen($category) > 0 && strlen($page) > 0 && is_numeric($category) && is_numeric($page);
        if ($validate) {
            $limit = $this->page($page);
            $where = 'b.book_state = 1';
            if ($category != 0) {
                $where .= " AND c.category_id = {$category}";
            }
            $bookList = M('Book_categories')->alias('c')
                ->distinct(true)
                ->field('b.id,b.book_name,a.author,b.readed_number,b.number_of_words,b.is_hot,book_banner')
                ->join('left join __BOOK__ b on c.book_id = b.id')
                ->join('left join __AUTHOR__ a on a.id = b.author_id')
                ->where($where)
                ->limit($limit['page'],$limit['limit'])
                ->order('id desc')
                ->select();
            exit(json_encode($bookList));
        }
        $categories = M('Category')->field('id, category')->limit(0,3)->select();
        $this->assign('categories', $categories);
        $this->display();
    }
}