<?php
/**
 * App公共访问接口
 * User: 帅
 * Date: 2017/3/11
 * Time: 9:55
 */

namespace App\Controller;


use Org\Util\String;

class IndexController extends CommonController
{
    /**
     * 初始index控制器，防止非法访问
     * @method post
     * @post String visit 值固定为'public'
     */
    public function __construct()
    {
        parent::__construct();
        $visit = I('post.visit');
        if (empty($visit) || $visit !== 'public') {
            $this->returnCode(2);
        }
    }

    /**
     * 首页数据接口
     * @method post
     * @post null
     * @return String json字符串 is_hot:是否为热门图书，默认0代表否，1代表最火，2最新，3免费，4更新
     */
    public function index()
    {
        //获取banner
        $bannerList = M('Banner')->field('banner_file, book_id')->where(['banner_state' => 1])->select();
        //获取热门图书列表
        $hotBookList = M('Book')->alias('b')
            ->field('b.id, b.book_banner, b.is_hot, b.book_name, b.readed_number, b.number_of_words, a.author')
            ->join('left join __AUTHOR__ a on a.id = b.author_id')
            ->where('b.is_hot > 0 AND book_state = 1')
            ->order('id desc')->select();
        //检查数据
        $bannerList = $this->checkData($bannerList);
        $hotBookList = $this->checkData($hotBookList);
        $this->returnInformation(0, 'OK', ['bannerList' => $bannerList, 'hotBookList' => $hotBookList]);
    }

    /**
     *书城数据接口
     * @method post
     * @post String||int category 图书类别
     * @post String||int page 页数
     * @return String json字符串
     */
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
            //总页数
            $count = M('Book_categories')->alias('c')
                ->distinct(true)
                ->join('left join __BOOK__ b on c.book_id = b.id')
                ->where($where)->count();
            $pageCount = ceil($count / $limit['limit']);


            //书籍列表
            $bookList = M('Book_categories')->alias('c')
                ->distinct(true)
                ->field('b.id,b.book_name,a.author,b.readed_number,b.number_of_words,b.is_hot,book_banner')
                ->join('left join __BOOK__ b on c.book_id = b.id')
                ->join('left join __AUTHOR__ a on a.id = b.author_id')
                ->where($where)
                ->limit($limit['page'], $limit['limit'])
                ->order('id desc')
                ->select();
            $bookList = $this->checkData($bookList);
            $this->returnInformation(0, 'OK', ['pageCount' => $pageCount, 'bookList' => $bookList]);
        }
        $this->returnCode(1);
    }

    /**
     * 书城顶部分类
     * @method post
     * @post null
     * @return String json字符串
     */
    public function library_c()
    {
        $categories = M('Category')->field('id, category')->limit(0, 3)->select();
        $categories = $this->checkData($categories);
        $this->returnInformation(0, 'OK', $categories);
    }

    /**
     * 搜索数据接口
     * @method post
     * @post String || null search 要搜索的数据
     * @return String json 字符串
     */
    public function search()
    {
        $search = I('post.search');
        //开始搜索
        if (!empty($search)) {
            $searchResult = M('Book')->alias('b')
                ->field('b.id, b.book_banner, b.is_hot, b.book_name, b.readed_number, b.number_of_words, a.author')
                ->join('left join __AUTHOR__ a on a.id = b.author_id')
                ->where("book_state = 1 AND (a.author like '%{$search}%' OR b.book_name like '%{$search}%')")
                ->order('id desc')->select();
            $searchResult = $this->checkData($searchResult);
            $this->returnInformation(0, 'OK', $searchResult);
        }
        //大家都在搜
        $allSearch = M('Book')->field('id,is_hot,book_name')
            ->where(['book_state' => 1, 'searched' => ['neq', 0]])
            ->order('searched desc')
            ->limit(6)->select();
        $allSearch = $this->checkData($allSearch);
        $this->returnInformation(0, 'OK', $allSearch);
    }

    /**
     * 大家都在搜追加
     * @method post
     * @post String || int bookId 书籍id
     * @return String json字符串
     */
    public function search_all()
    {
        $bookId = I('post.bookId');
        if (!empty($bookId) && is_numeric($bookId)) {
            $setInfo = M('Book')->where(['id' => $bookId])->setInc('searched', 1);
            if ($setInfo) {
                $this->returnCode(0);
            }
            $this->returnCode(3);
        }
        $this->returnCode(1);
    }

    /**
     *书籍详情数据接口
     * @method post
     * @post String || int bookId 书籍id
     * @post String token
     * @return String json字符串
     */
    public function book()
    {
        $bookId = I('post.bookId');
        if (!empty($bookId) && is_numeric($bookId)) {
            //图书信息
            $bookInfo = M('Book')->alias('b')
                ->field('b.id, b.book_cover,b.book_name,a.author,b.shelves_time,b.number_of_words,b.book_synopsis,a.author_synopsis')
                ->join('left join __AUTHOR__ a on a.id = b.author_id')
                ->where(['b.id' => $bookId, 'b.book_state' => 1])
                ->find();
            //分类
            $categories = M('Category')->alias('c')
                ->field('c.category')
                ->join('right join __BOOK_CATEGORIES__ b on c.id = b.category_id')
                ->where(['b.book_id' => $bookId])
                ->select();
            //判断图书是否已在书架
            $isExistsBookshelf = '0';
            if (!empty($this->token)) {
                $isExists = M('Bookshelf')->where(['user_id' => $this->token, 'book_id' => $bookId])->getField('id');
                if ($isExists) {
                    $isExistsBookshelf = '1';
                }
            }
            //检查数据
            $bookInfo = $this->checkData($bookInfo);
            $bookInfo['shelves_time'] = date('Y.m.d', $bookInfo['shelves_time']);
            $categories = $this->changeArray($categories,'category');
            $this->returnInformation(0, 'OK', ['bookInfo' => $bookInfo, 'categories' => $categories, 'isExistsBookshelf' => $isExistsBookshelf]);
        }
        $this->returnCode(1);
    }

    /**
     *书籍目录数据接口
     * @method post
     * @post String || int page 页数
     * @post String || int bookId 图书id
     * @return String json字符串
     */
    public function catalogue()
    {
        $page = I('post.page');
        $bookId = I('post.bookId');
        $validate = !empty($page) && is_numeric($page) && !empty($bookId) && is_numeric($bookId);
        if ($validate) {
            $limit = $this->page($page);
            $chapter = M('Chapter')
                ->field('chapter,chapter_sort')
                ->where(['book_id' => $bookId])
                ->order('chapter_sort asc')
                ->limit($limit['page'], $limit['limit'])
                ->select();
            $chapter = $this->checkData($chapter);
            $this->returnInformation(0, 'OK', $chapter);
        }
        $this->returnCode(1);
    }

    /**
     *阅读数据接口
     * @method post
     * @post String || int bookId 书籍id
     * @post String || int chapterSort 章节数
     * @post String token
     * @return String json字符串
     */
    public function book_r()
    {
        $bookId = I('post.bookId');
        $chapter = empty(I('post.chapterSort')) ? 1 : I('post.chapterSort');
        $validate = !empty($bookId) && is_numeric($bookId) && is_numeric($chapter);
        if ($validate) {
            $chapterObject = M('Chapter');
            $finalChapter = $chapterObject->where(['book_id' => $bookId])->order('chapter_sort desc')->getField('chapter_sort');
            $chapter = $chapter > $finalChapter ? $finalChapter : $chapter;    //判断最终章
            $chapterInfo = $chapterObject->field('chapter, chapter_content, chapter_sort')
                ->where(['book_id' => $bookId, 'chapter_sort' => $chapter])
                ->find();
            $nextChapter = $chapterObject->where(['book_id' => $bookId, 'chapter_sort' => ($chapter + 1)])->getField('chapter');
            $bookName = M('Book')->where(['id' => I('get.b_id')])->getField('book_name');
            //判断该书是否存在于书架
            $isExistsBookshelf = '0';
            if (!empty($this->token)) {
                $isExists = M('Bookshelf')->where(['user_id' => $this->token, 'book_id' => $bookId])->getField('id');
                if ($isExists) {
                    $isExistsBookshelf = '1';
                }
            }

            $chapterInfo = $this->checkData($chapterInfo);
            $this->returnInformation(0,'OK',['nextChapter'=>$nextChapter,'isExistsBookshelf'=>$isExistsBookshelf,'chapterInfo'=>$chapterInfo,'bookName'=>$bookName]);
        }
        $this->returnCode(1);

    }

    /**
     * 用户注册接口
     * @method post
     * @post String openid 用户微信登陆的openid
     * @post String nickName 用户昵称
     * @post String || Int sex 用户性别,传入int值时：1：男，2：女，3：未填写
     */
    public function register()
    {
        $openid = I('post.openid');
        $nickName = I('post.nickName');
        $sex = I('post.sex');
        $avatar = I('post.avatar');
        $vaildate = !empty($openid) && !empty($nickName) && !empty($sex) && !empty($avatar);
        if ($vaildate) {
            $user = M('User');
            $userFind = $user->where(['openid'=>$openid])->getField('id');
            $time = time();
            //添加操作
            if (!$userFind) {
                try{
                    $userFind = $user->add([
                        'openid'=>$openid,
                        'nick_name'=>$nickName,
                        'avatar_file'=>$avatar,
                        'sex'=>$sex,
                        'register_time' => $time,
                    ]);
                }catch (\Exception $e) {
                    //防止昵称问题无法添加
                    $newNickName = '用户'.rand(1000,9999);
                    $userFind = $user->add([
                        'openid'=>$openid,
                        'nick_name'=>$newNickName,
                        'avatar_file'=>$avatar,
                        'sex'=>$sex,
                        'register_time' => $time,
                    ]);
                }
            }
            $token = $this->keyword_encode($time.$userFind, 'ENCODE');
            $this->returnInformation(0,'OK',['token'=>$token]);
        }
        $this->returnCode(1);
    }

    /**
     *美文列表
     * @method post
     * @post null
     * @return String json字符串
     */
    public function b_words()
    {
        $page = I('post.page');
        if (!empty($page) && is_numeric($page)) {
            $limit = $this->page($page);
            $beautifulWords = M('Beautiful_words');
            $beautifulWordsList = $beautifulWords
                ->field('id,title,author,cover,see,editor_way,url,listen,praise,sent_time')
                ->order('id desc')
                ->limit($limit['page'],$limit['limit'])
                ->select();
            $pageCount = $beautifulWords->count();
            $beautifulWordsList = $this->checkData($beautifulWordsList);
            $count = ceil($pageCount/$limit['limit']);
            $this->returnInformation(0,'OK',['beautifulWordsList'=>$beautifulWordsList,'count'=>$count]);
        }
        $this->returnCode(1);
    }

    /**
     *美文详情
     * @method post
     * @post String||Int articleId 文章id
     * @return String json字符串
     */
    public function article()
    {
        $articleId = I('post.articleId');
        if (!empty($articleId) && is_numeric($articleId)) {
            $article = M('Beautiful_words')->field('id,title,content,audio,see,listen,praise')->where(['id'=>$articleId])->find();
            if ($article) {
                $article['content'] = htmlspecialchars_decode($article['content']);
                $this->returnInformation(0,'OK',$article);
            }
            $this->returnCode('文章不存在！');
        }
        $this->returnCode(1);
    }

    /**
     *美文：赞，收听，阅读人数递增接口
     * @method post
     * @post String type 递增类型
     * @post String||Int articleId 文章id
     * @return String json字符串
     */
    public function article_up()
    {
        $type = I('post.type');
        $articleId = I('post.articleId');
        if (!empty($type) && !empty($articleId) && is_numeric($articleId)) {
            $beautifulWords = M('Beautiful_words');
            $addInfo = false;
            switch ($type)
            {
                case 'readed':
                    $addInfo = $beautifulWords->where(['id'=>$articleId])->setInc('see',1);
                    break;
                case 'listened':
                    $addInfo = $beautifulWords->where(['id'=>$articleId])->setInc('listen',1);
                    break;
                case 'like':
                    $addInfo = $beautifulWords->where(['id'=>$articleId])->setInc('praise',1);
                    break;
            }
            if ($addInfo) {
                $this->returnCode(0);
            }
            $this->returnCode(3);
        }
        $this->returnCode(1);
    }

    /**
     *
     *
     */
}