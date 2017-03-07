<?php
/**
 * Created by PhpStorm.
 * User: 帅
 * Date: 2017/3/2
 * Time: 12:29
 */

namespace Home\Controller;


class BannerController extends CommonController
{
    public function b_list()
    {
        //异步对banner操作处理
        $validate = !empty(I('post.type')) && !empty(I('post.id'));
        if ($validate) {
            if (!is_numeric(I('post.id'))) {
                exit('FAIL');
            }
            $type = I('post.type');
            $id = I('post.id');
            $state = I('post.state');
            $info = null;
            $bannerObject = M('Banner');
            if ($type == 'del') {    //判断删除
                $filePath = $bannerObject->where(['id' => $id])->getField('banner_file');
                @unlink(PATH_DIR.$filePath);
                $info = $bannerObject->where(['id' => $id])->delete();
            } elseif ($type == 'up_down') {    //判断修改状态
                $info = $bannerObject->where(['id' => $id])->save(['banner_state' => $state]);
            }
            if ($info) {
                exit('SUCCESS');
            }
            exit('FAIL');
        }
        $bannerList = M('Banner')->alias('b')
            ->field('b.id, b.banner_file, b.banner_state, k.book_name')
            ->join('left join __BOOK__ k on k.id = b.book_id')
            ->select();
        $this->assign('bannerList', $bannerList);
        $this->display();
    }

    public function b_add()
    {
        if (!empty($_FILES) && $_FILES['banner']['error'] == 0 && $_FILES['banner']['size'] > 0) {
            $info = $this->fileUpload($_FILES['banner']);
            if (!is_array($info)) {
                return $this->error($info);
            }
            $fileInfo = $info['savepath'].$info['savename'];
            try {
                $addInfo = M('Banner')->add([
                    'book_id' => I('post.book_id'),
                    'banner_file' => $fileInfo,
                ]);
                if (!$addInfo) {
                    return $this->error('banner添加失败！');
                }
                return $this->redirect('Banner/b_list');
            }catch (\Exception $e) {
                return $this->error('banner添加失败！');
            }

        }
        $bookList = M('Book')->field('id,book_name')->select();
        if (empty($bookList)) {
            $bookList = array(
                array('id' => 0, 'book_name' => '暂无跳转'),
            );
        }
        $this->assign('bookList', $bookList);
        $this->display();
    }
}