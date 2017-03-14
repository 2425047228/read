<?php
/**
 * Created by PhpStorm.
 * User: 帅
 * Date: 2017/3/11
 * Time: 9:51
 */

namespace App\Controller;



class UserController extends CommonController
{
    //初始化控制器
   /* public function _initialize()
    {
        if (empty($this->token)) {
            $this->returnInformation(0,'token不存在');
        }
    }*/

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
                $this->returnInformation(1,'该书已在书架');
            }
            $addInfo = $bookshelf->add(['book_id'=>$bookId,'user_id'=>$this->token,'join_time'=>time()]);
            if ($addInfo) {
                $this->returnInformation(0,'OK');
            }
            $this->returnInformation(1,'FAIL');
        }
        $this->returnInformation(1,'参数错误');
    }

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
            $this->returnInformation(1,'文件不存在');
        }
        $this->returnInformation(1,'参数错误');

    }
}