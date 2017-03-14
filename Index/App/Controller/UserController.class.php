<?php
/**
 * App用户登陆后访问的控制器
 * User: 帅
 * Date: 2017/3/11
 * Time: 9:51
 */

namespace App\Controller;



class UserController extends CommonController
{
    //测试token：4efcjmnLua2MJ8PObBKqVWtVZemqXpVNWl87RZbpbxROEvdrCZJUvA
    /**
     * 初始化控制器方法
     * @method post
     * @post String token 用户绑定的token
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
        if (empty($this->token)) {
            $this->returnCode(4);
        }
    }

    /**
     *添加至书架操作接口
     * @method post
     * @post String || int bookId 书籍id
     * @return String json字符串
     */
    public function bookshelf_a()
    {
        $bookId = I('post.bookId');
        if (!empty($bookId) && is_numeric($bookId)) {
            $bookshelf = M('Bookshelf');
            $isExistsBookshelf = $bookshelf->where(['book_id'=>$bookId,'user_id'=>$this->token])->getField('id');
            if ($isExistsBookshelf) {
                $this->returnCode('该书已在书架！');
            }
            $addInfo = $bookshelf->add(['book_id'=>$bookId,'user_id'=>$this->token,'join_time'=>time()]);
            if ($addInfo) {
                $this->returnCode(0);
            }
            $this->returnCode(3);
        }
        $this->returnCode(1);
    }

    /**
     *图书移除书架接口
     * @method post
     * @post String bookId 书籍id
     * @return String json字符串
     */
    public function bookshelf_d()
    {
        $bookId = I('post.bookId');
        if (!empty($bookId) && is_numeric($bookId)) {
            $delInfo = M('Bookshelf')->where(['book_id'=>$bookId,'user_id'=>$this->token])->delete();
            if ($delInfo) {
                $this->returnCode(0);
            }
            $this->returnCode(3);
        }
        $this->returnCode(1);
    }

    /**
     * 书架
     * @method post
     * @post null
     * @return String json字符串
     */
    public function bookshelf()
    {
        $bookshelf = M('Bookshelf')->alias('f')
            ->field('b.id, b.book_banner, b.is_hot, b.book_name, b.readed_number, b.number_of_words, a.author')
            ->join('left join __BOOK__ b on b.id = f.book_id')
            ->join('left join __AUTHOR__ a on a.id = b.author_id')
            ->where("f.user_id = {$this->token} AND b.book_state = 1")
            ->order('b.id desc')
            ->select();
        $bookshelf = $this->checkData($bookshelf);
        $this->returnInformation(0,'OK',$bookshelf);
    }

    /**
     * 图书下载接口
     * @method:post
     * @post: String || Int bookId 书籍id
     * @return File 图书文件
     */
    public function book_dl()
    {
        $bookId = I('post.bookId');
        if (!empty($bookId) && is_numeric($bookId)) {
            $bookFile = M('Book')->field('book_name,book_file')->where(['id'=>$bookId])->find();
            $file = PATH_DIR.$bookFile['book_file'];
            if ($bookFile && file_exists($file)) {
                header('Accept-Ranges: bytes');
                header('Content-Length: '.filesize($file));
                header('Content-Transfer-Encoding: binary');
                header('Content-type: application/octet-stream');
                header("Content-Disposition: attachment;filename='{$bookFile['book_name']}.txt'");
                header("Content-Type: application/octet-stream;name='{$bookFile['book_name']}.txt'");
                readfile($file);
                exit;
            }
            $this->returnCode('文件不存在！');
        }
        $this->returnCode(1);
    }

    /**
     * 个人中心数据接口
     * @method post
     * @post null
     * @return String json字符串
     */
    public function user_c()
    {
        $profile = M('User')->field('avatar_file,nick_name,sex')->where(['id'=>$this->token])->find();
        $hobbys = M('User_hobbys')->alias('u')
            ->field('c.category')
            ->join('left join __CATEGORY__ c on c.id = u.category_id')
            ->where(['u.user_id'=>$this->token])
            ->select();
        $hobbys = $this->changeArray($hobbys,'category');
        $profile = $this->checkData($profile);
        $this->returnInformation(0,'OK',['hobbys'=>$hobbys,'profile'=>$profile]);
    }

    /**
     * 用户信息修改
     * @method post
     * @post String nickName 用户昵称
     * @post String||Int sex 性别
     * @
     * @return String json字符串
     */
    public function profile()
    {
        
    }
}