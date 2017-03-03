<?php
/**
 * Created by PhpStorm.
 * User: 帅
 * Date: 2017/3/2
 * Time: 17:50
 */

namespace Home\Controller;


class AuthorController extends CommonController
{
    //作者列表
    public function a_list()
    {
        if (!empty(I('post.id'))) {
            $id = I('post.id');
            if (!is_numeric($id)) {
                exit('删除失败！');
            }
            $bookInfo = M('Book')->where(['author_id' => $id])->find();
            if ($bookInfo) {
                exit('该作者已有出版图书！');
            }
            $authorInfo = M('Author')->where(['id' => $id])->delete();
            if ($authorInfo) {
                exit('SUCCESS');
            }
            exit('删除失败！');
        }
        $authorList = M('Author')->order('id desc')->select();
        $this->assign('authorList', $authorList);
        $this->display();
    }

    //添加作者
    public function a_add()
    {
        $vaildate = !empty(I('post.author')) && !empty(I('post.author_synopsis'));
        if ($vaildate) {
            try {
                $authorAdd = M('Author')->add([
                    'author' => I('post.author'),
                    'author_synopsis' => I('post.author_synopsis'),
                ]);
                if ($authorAdd) {
                    return $this->redirect('Author/a_list');
                }
                return $this->error('添加失败！');
            } catch (\Exception $e){
                return $this->error('添加失败！');
            }
        }
        $this->display();
    }
    
    //修改作者信息
    public function a_update()
    {
        $vaildate = !empty(I('post.author')) && !empty(I('post.author_synopsis')) && !empty(I('post.id'));
        if ($vaildate) {
            try{
                $updateInfo = M('Author')->save([
                    'id' => I('post.id'),
                    'author' => I('post.author'),
                    'author_synopsis' => I('post.author_synopsis'),
                ]);
                if ($updateInfo !== false) {
                    return $this->redirect('Author/a_list');
                }
                return $this->error('修改失败！');
            }catch (\Exception $e) {
                return $this->error('修改失败！');
            }
        }
        $author = M('Author')->where(['id' => I('get.id')])->find();
        $this->assign('author', $author);
        $this->display();
    }
}