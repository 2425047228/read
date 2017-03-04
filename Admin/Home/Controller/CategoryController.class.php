<?php
/**
 * Created by PhpStorm.
 * User: 帅
 * Date: 2017/3/2
 * Time: 19:55
 */

namespace Home\Controller;


class CategoryController extends CommonController
{
    //爱好管理
    public function category()
    {
        if (!empty(I('post.id'))) {
            $id = I('post.id');
            if (!is_numeric($id)) {
                exit('删除失败！');
            }
            $personalsInfo = M('Category')->where(['id' => $id])->getField('category_personals');
            $bookInfo = M('Book')->where(['category_id' => $id])->find();
            if ($personalsInfo) {
                exit('已有兴趣人群无法删除！');
            }
            if ($bookInfo) {
                exit('已有相关类型书籍无法删除！');
            }
            $deleteInfo = M('Category')->where(['id' => $id])->delete();
            if ($deleteInfo) {
                exit('SUCCESS');
            }
            exit('删除失败！');
        }
        $categoryList = M('Category')->select();
        $this->assign('categoryList', $categoryList);
        $this->display();
    }
    
    //添加爱好
    public function c_add()
    {
        if (!empty(I('post.category'))) {
            try{
                $categoryInfo = M('Category')->add(['category'=>I('post.category')]);
                if ($categoryInfo) {
                    return $this->redirect('Category/category');
                }
                return $this->error('添加失败！');
            }catch (\Exception $e){
                return $this->error('添加失败！');
            }
        }
        $this->display();
    }

    //修改爱好
    public function c_update()
    {
        $vaildate = !empty(I('post.category')) && !empty(I('post.id'));
        if ($vaildate) {
            try{
                $updateInfo = M('Category')->save([
                    'id' => I('post.id'),
                    'category' => I('post.category'),
                ]);
                if ($updateInfo !== false) {
                    return $this->redirect('Category/category');
                }
                return $this->error('修改失败！');
            }catch (\Exception $e) {
                return $this->error('修改失败！');
            }
        }
        $category = M('Category')->where(['id' => I('get.id')])->find();
        $this->assign('category', $category);
        $this->display();
    }
}