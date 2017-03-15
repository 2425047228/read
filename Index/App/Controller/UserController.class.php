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
            ->field('c.id,c.category')
            ->join('left join __CATEGORY__ c on c.id = u.category_id')
            ->where(['u.user_id'=>$this->token])
            ->select();
        $hobbys = $this->checkData($hobbys);
        $profile = $this->checkData($profile);
        $this->returnInformation(0,'OK',['hobbys'=>$hobbys,'profile'=>$profile]);
    }

    /**
     * 兴趣列表接口
     * @method post
     * @post null
     * @return String json字符串
     */
    public function hobbys()
    {
        $categories = M('Category')->field('id,category')->select();
        $categories = $this->checkData($categories);
        $this->returnInformation(0,'OK',$categories);
    }

    /**
     * 用户信息修改
     * @method post
     * @post String nickName 用户昵称
     * @post String||Int sex 性别
     * @post String categories 兴趣集合的json格式数组字符串,索引value为兴趣分类id
     * @return String json字符串
     */
    public function profile()
    {
        $saveData = array();
        //昵称
        if (!empty(I('post.nickName'))) {
            $saveData['nick_name'] = I('post.nickName');
        }
        //性别
        if (!empty(I('post.sex'))) {
            $saveData['sex'] = I('post.sex');
        }

        $categories = htmlspecialchars_decode(I('post.categories'));    //转换html字符实体

        //开启事务
        M()->startTrans();
        if (!empty($saveData)) {
            $saveProfile = M('User')->where(['id'=>$this->token])->save($saveData);
            if ($saveProfile === false) {
                M()->rollback();
                $this->returnCode('资料修改失败!');
            }
        }

        //兴趣
        if (strlen($categories) > 0) {
            $categories = json_decode($categories,true);
            if (is_array($categories)) {
                $categoriesLen = count($categories);
                $userHobbys = M('User_hobbys');
                if ($categoriesLen > 5) {
                    $this->returnCode('categories过长！');
                }
                $delCategories = $userHobbys->where(['user_id'=>$this->token])->delete();
                if ($delCategories === false) {
                    M()->rollback();
                    $this->returnCode('兴趣爱好修改失败!');
                }
                if ($categoriesLen > 0) {
                    foreach ($categories as $v){
                        $addCategories = $userHobbys->add(['user_id'=>$this->token,'category_id'=>$v]);
                        if (!$addCategories) {
                            M()->rollback();
                            $this->returnCode('兴趣爱好添加失败!');
                        }
                    }
                }
            } else {
                M()->rollback();
                $this->returnCode('categories参数json格式不正确！');
            }
        }
        M()->commit();
        $this->returnCode(0);
    }
    
    /**
     * 修改头像接口
     * @method post
     * @post File avatar 文件key，文件为jpg,jpeg,png,gif 格式
     * @return String json字符串
     */
    public function avatar()
    {
        $fileName = $_FILES['avatar'];
        $validate = !empty($_FILES) && $fileName['error'] == 0 && $fileName['size'] > 0;
        if ($validate) {
            $uploadInfo = $this->fileUpload($fileName);
            if (is_array($uploadInfo)) {
                $user = M('User');
                $httpHost = 'http://'.$_SERVER['HTTP_HOST'];
                $newAvatar = $this->setUploadFileUrl($uploadInfo);
                $oldAvatar = $user->where(['id'=>$this->token])->getField('avatar_file');
                $saveAvatar = $user->where(['id'=>$this->token])->save(['avatar_file'=>$newAvatar]);
                if (!empty($oldAvatar) && $saveAvatar) {
                    $oldAvatarUri = str_replace($httpHost,'',$oldAvatar);
                    @unlink(PATH_DIR.$oldAvatarUri);
                }
                $this->returnInformation(0,'OK',['avatar'=>$newAvatar]);
            }
            $this->returnCode($uploadInfo);
        }
        $this->returnCode(1);
    }
    
    /**
     * 绑定手机，发送验证码接口
     * @method post
     * @post String mobileNumber 用户手机号
     * @return String json字符串
     */
    public function bind_m()
    {
        $mobileNumber = I('post.mobileNumber');
        $validate = !empty($mobileNumber) && is_numeric($mobileNumber) && strlen($mobileNumber) < 12;
        if ($validate) {
            $codeArray = $this->sendCode($mobileNumber);
            if ($codeArray['validateCode'] !== -1) {
                $this->returnInformation(0,'OK',['code'=>md5($codeArray['validateCode'])]);
            }
            $this->returnCode($codeArray['detail']);
        }
        $this->returnCode(1);
    }

    /**
     * 用户绑定手机接口
     * @method post
     * @post String mobileNumber 用户手机号
     * @return String json字符串
     */
    public function bind_p()
    {
        $mobileNumber = I('post.mobileNumber');
        $validate = !empty($mobileNumber) && is_numeric($mobileNumber) && strlen($mobileNumber) < 12;
        if ($validate) {
            $bind = M('User')->where(['id'=>$this->token])->save(['mobile_number'=>$mobileNumber]);
            if ($bind !== false) {
                $this->returnCode(0);
            }
            $this->returnCode(3);
        }
        $this->returnCode(1);
    }

    /**
     * 意见反馈
     * @method post
     * @post String content 反馈内容
     * @return String json字符串
     */
    public function feedback()
    {
        $content = I('post.content');
        if (!empty($content)) {
            try{
                $feedbackInfo = M('Back')->add(['u_id'=>$this->token,'content'=>$content,'time'=>time()]);
                if ($feedbackInfo) {
                    $this->returnCode(0);
                }
                $this->returnCode(3);
            }catch (\Exception $e){
                $this->returnCode('内容过长！');
            }

        }
        $this->returnCode(1);
    }
}