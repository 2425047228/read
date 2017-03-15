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
	
	private function fenxiang(){
		
		
		$fenxiang['title'] = "芝麻阅读，多类型书籍供你选择。";
		$fenxiang['desc'] = "青春校园，都市言情，玄幻仙侠等，想看哪种选哪种！";
		$fenxiang['img'] = "http://".$_SERVER['HTTP_HOST']."/Public/Index/images/share.png";
		$fenxiang['url'] = $_SERVER['HTTP_HOST'].U("library");
		return $fenxiang;
	}
	
    //图书详情
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
			
			//分享
			$share = $this -> share();
			$this -> assign("signPackage",$share);
			$fenxiang['img'] = "http://".$_SERVER['HTTP_HOST']."/Public/Index/images/share.png";
			$fenxiang['url'] = $_SERVER['HTTP_HOST'].U("b_detail",array("b_id"=>$bookId));
			$fenxiang['title'] = $bookInfo['book_name'];
			$fenxiang['desc'] = "看更多详情，读更多好书，尽在芝麻阅读。";
			$this -> assign("fenxiang",$fenxiang);
            $this->display();
        }
    }
    //阅读
    public function b_read()
    {
        $bookId = I('get.b_id');
        $validate = !empty($bookId) && is_numeric($bookId);
        if ($validate) {
            $chapterObject = M('Chapter');
            $cookieTime = C('COOKIE_TIME');
            $finalChapter = $chapterObject->where(['book_id'=>$bookId])->order('chapter_sort desc')->getField('chapter_sort');
            $bookInfo = M('Book')->field('book_name')->where(['id' => I('get.b_id')])->find();
            $chapter = I('get.chapter');    // == 0 ? 1:I('get.chapter');    //判断首章
            $chapter = $chapter > $finalChapter ? $finalChapter : $chapter;    //判断最终章
            $chapterCookieName = 'recordBookChapter'.$bookId;    //获取当前书籍历史章节的cookie名
            if (empty($chapter) || !is_numeric($chapter)) {    //判断章节
                $recordBookChapter = cookie($chapterCookieName);    //历史章节
                $chapter = !empty($recordBookChapter) ? $recordBookChapter:1;

            }

            $chapterInfo = $chapterObject->field('chapter, chapter_content, chapter_sort')
                ->where(['book_id'=>$bookId, 'chapter_sort'=>$chapter])
                ->find();
            $nextChapter = $chapterObject->where(['book_id'=>$bookId, 'chapter_sort'=>($chapter+1)])->getField('chapter');

            //记录足迹
            cookie('readRecord', $bookId, $cookieTime);
            cookie('readRecordTime', time(), $cookieTime);
            //当前浏览至章节
            cookie($chapterCookieName,$chapter,$cookieTime);

            //判断来源
            $nowAction = CONTROLLER_NAME . '/' . ACTION_NAME;
            $previousAction = $_SERVER['HTTP_REFERER'];
            if (!strpos($previousAction, $nowAction) && !strpos($previousAction, 'Book/catalogue')) {
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
			
			//分享
			$share = $this -> share();
			$this -> assign("signPackage",$share);
			$fenxiang['img'] = "http://".$_SERVER['HTTP_HOST']."/Public/Index/images/share.png";
			$fenxiang['url'] = $_SERVER['HTTP_HOST'].U("b_detail",array("b_id"=>$bookId));
			$fenxiang['title'] = $bookInfo['book_name'];
			$fenxiang['desc'] = "看更多详情，读更多好书，尽在芝麻阅读。";
			$this -> assign("fenxiang",$fenxiang);
			
			
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
            $userId = cookie('userId');
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
    //书城
    public function library()
    {
		
		$share = $this -> share();
		$this -> assign("signPackage",$share);
		$fenxiang = $this -> fenxiang();
		$this -> assign("fenxiang",$fenxiang);
		
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
    //书架
    public function bookshelf()
    {
        $userId = cookie('userId');
        if (!empty(I('post.id')) && is_numeric(I('post.id'))) {
            $delInfo = M('Bookshelf')->where(['book_id'=>I('post.id'),'user_id'=>$userId])->delete();
            if ($delInfo) {
                exit('SUCCESS');
            }
        }
        $readRecord = cookie('readRecord');
        //足迹
        $recordBookInfo = null;
        if (!empty($readRecord)) {
            $recordBookInfo = M('Book')->alias('b')
                ->field('b.id, b.book_banner, b.book_name, a.author')
                ->join('left join __AUTHOR__ a on a.id = b.author_id')
                ->where(['b.id'=>$readRecord,'b.book_state'=>1])
                ->find();
        }

        //书架图书
        $bookshelf = M('Bookshelf')->alias('f')
            ->field('b.id, b.book_banner, b.is_hot, b.book_name, b.readed_number, b.number_of_words, a.author')
            ->join('left join __BOOK__ b on b.id = f.book_id')
            ->join('left join __AUTHOR__ a on a.id = b.author_id')
            ->where("f.user_id = {$userId} AND b.book_state = 1")
            ->order('b.id desc')
            ->select();

        $this->assign('bookshelf', $bookshelf);
        $this->assign('recordBookInfo', $recordBookInfo);
        $this->display();
    }
    //搜索
    public function b_search()
    {
        //搜索历史
        $searchRecord = json_decode(cookie('searchRecord'));
        if (empty($searchRecord)) {
            $searchRecord = array();
        }
        if (IS_POST) {
            //判断点击搜索结果
            if (!empty(I('post.id')) && is_numeric(I('post.id'))) {
                M('Book')->where(['id'=>I('post.id')])->setInc('searched',1);
                exit('SUCCESS');
            }
            //清除搜索历史
            if (!empty(I('post.type')) && I('post.type') == 'removeSearchRecord') {
                cookie('searchRecord',null);
                exit('SUCCESS');
            }

            //搜索
            if (!empty(I('post.search'))) {
                $searchData = I('post.search');
                $searchResult = M('Book')->alias('b')
                    ->field('b.id, b.book_banner, b.is_hot, b.book_name, b.readed_number, b.number_of_words, a.author')
                    ->join('left join __AUTHOR__ a on a.id = b.author_id')
                    ->where("book_state = 1 AND (a.author like '%{$searchData}%' OR b.book_name like '%{$searchData}%')")
                    ->order('id desc')->select();
                //追加至历史中
                if (!in_array($searchData,$searchRecord)) {
                    array_push($searchRecord,$searchData);
                    cookie('searchRecord',json_encode($searchRecord),C('COOKIE_TIME'));
                }
                exit(json_encode($searchResult));
            }
        }

        //大家都在搜
        $allSearch = M('Book')->field('id,is_hot,book_name')
            ->where(['book_state'=>1,'searched'=>['neq',0]])
            ->order('searched desc')
            ->limit(6)->select();

        $this->assign('searchRecord', $searchRecord);
        $this->assign('allSearch', $allSearch);
        $this->display();
    }
    //图书目录
    public function catalogue()
    {
        $page = I('post.page');
        $b_id = I('post.b_id');
        $validate = !empty($page) && is_numeric($page) && !empty($b_id) && is_numeric($b_id);
        if ($validate) {
            $limit = $this->page($page);
            $chapter = M('Chapter')
                ->field('chapter,chapter_sort')
                ->where(['book_id'=>$b_id])
                ->order('chapter_sort asc')
                ->limit($limit['page'],$limit['limit'])
                ->select();
            exit(json_encode($chapter));
        }
        if (!empty(I('get.b_id')) && is_numeric(I('get.b_id'))) {
            $bookId = I('get.b_id');
            $bookName = M('Book')->where(['id'=>$bookId])->getField('book_name');
            $this->assign('bookName', $bookName);
            $this->assign('bookId',$bookId);
            $this->display();
        }
    }
}