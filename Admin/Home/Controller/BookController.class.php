<?php
/**
 * Created by PhpStorm.
 * User: 帅
 * Date: 2017/3/3
 * Time: 10:11
 */

namespace Home\Controller;


class BookController extends CommonController
{
    public function b_list()
    {
        //异步对book操作处理
        $validate = strlen(I('post.state')) == 1 && !empty(I('post.id'));
        if ($validate) {
            if (!is_numeric(I('post.id'))) {
                exit('FAIL');
            }
            $id = I('post.id');
            $state = I('post.state');
            $info = M('Book')->save(['id' => $id, 'book_state' => $state]);
            if ($info) {
                exit('SUCCESS');
            }
            exit('FAIL');
        }
        $bookList = M('Book')->field('id, book_name, number_of_words, is_hot, book_state')->order('id desc')->select();
        $this->assign('bookList', $bookList);
        $this->display();
    }

    //添加图书
    public function b_add()
    {
        //添加请求
        if (IS_POST) {
            $validate = !empty(I('post.book_name')) && !empty(I('post.author_id')) && !empty(I('post.number_of_words')) && !empty(I('post.category_ids'));
            $isHotValidate = strlen(I('post.is_hot_validate')) == 1 && !empty(I('post.is_hot')) && !empty(I('post.book_synopsis'));
            $bookFileValidate = $_FILES['book_file']['error'] == 0 && $_FILES['book_file']['size'] > 0;
            $bookCoverValidate = $_FILES['book_cover']['error'] == 0 && $_FILES['book_cover']['size'] > 0;
            $bookBannerValidate = $_FILES['book_banner']['error'] == 0 && $_FILES['book_banner']['size'] > 0;
            //判断数据是否有缺失
            if ($validate && $isHotValidate && $bookFileValidate && $bookCoverValidate && $bookBannerValidate) {
                $txtFileInfo = $this->fileUpload($_FILES['book_file'], true, array('txt'), 'txt/');
                $imageFileInfo = $this->fileUpload($_FILES['book_cover']);
                $bannerFileInfo = $this->fileUpload($_FILES['book_banner']);
                if (!is_array($txtFileInfo) || !is_array($imageFileInfo) || !is_array($bannerFileInfo)) {
                    $this->returnCode(0, '文件上传失败！');
                }
                $bookFile = $txtFileInfo['savepath'].$txtFileInfo['savename'];
                $bookCover = $imageFileInfo['savepath'].$imageFileInfo['savename'];
                $bookBanner = $bannerFileInfo['savepath'].$bannerFileInfo['savename'];
                $isHot = I('post.is_hot_validate') == 1 ? I('post.is_hot') : 0;    //是否为最火
                $categories = I('post.category_ids');
                M()->startTrans();
                try {
                    $bookInfo = M('Book')->add([
                        'book_name' => I('post.book_name'),
                        'author_id' => I('post.author_id'),
                        'book_synopsis' => I('post.book_synopsis'),
                        'book_cover' => $bookCover,
                        'book_banner' => $bookBanner,
                        'is_hot' => $isHot,
                        'book_file' => $bookFile,
                        'number_of_words' => I('post.number_of_words'),
                        'shelves_time' => time(),
                    ]);
                    if (!$bookInfo) {
                        M()->rollback();
                        $this->returnCode(0, '添加失败！');
                    }
                    $correlation = M('Book_categories');
                    foreach ($categories as $v) {
                        $addInfo = $correlation->add(['book_id'=>$bookInfo, 'category_id' => $v]);
                        if (!$addInfo) {
                            M()->rollback();
                            $this->returnCode(0, '添加失败！');
                        }
                    }
                } catch (\Exception $e) {
                    M()->rollback();
                    $this->returnCode(0, '数据格式错误，添加失败！');
                }
                M()->commit();
                $this->returnCode(1, '添加成功！', array('bookId'=>$bookInfo));

            } else {
                $this->returnCode(0, '缺少数据，添加失败！');
            }
        }

        //类别列表
        $categoryList = M('Category')->field('id, category')->select();
        //作者列表
        $authorList = M('Author')->field('id, author')->select();

        $this->assign('categoryList', $categoryList);
        $this->assign('authorList', $authorList);
        $this->display();
    }

    //图书修改
    public function b_update()
    {
        if (IS_POST) {
            $validate = !empty(I('post.id')) && !empty(I('post.book_name')) && !empty(I('post.author_id')) && !empty(I('post.number_of_words')) && !empty(I('post.category_ids'));
            $isHotValidate = strlen(I('post.is_hot_validate')) == 1 && !empty(I('post.is_hot')) && !empty(I('post.book_synopsis'));
            $bookCoverValidate = $_FILES['book_cover']['error'] == 0 && $_FILES['book_cover']['size'] > 0;
            $bookBannerValidate = $_FILES['book_banner']['error'] == 0 && $_FILES['book_banner']['size'] > 0;
            //判断数据是否有缺失
            if ($validate && $isHotValidate) {
                $isHot = I('post.is_hot_validate') == 1 ? I('post.is_hot') : 0;    //是否为最火
                $categories = I('post.category_ids');
                $addData = array(
                    'id' => I('post.id'),
                    'book_name' => I('post.book_name'),
                    'author_id' => I('post.author_id'),
                    'book_synopsis' => I('post.book_synopsis'),
                    'is_hot' => $isHot,
                    'number_of_words' => I('post.number_of_words'),
                );
                $oldCover = $oldBanner = '';
                if ($bookCoverValidate) {
                    $imageFileInfo = $this->fileUpload($_FILES['book_cover']);
                    if (!is_array($imageFileInfo)) {
                        $this->returnCode(0, '文件上传失败！');
                    }
                    $addData['book_cover'] = $imageFileInfo['savepath'].$imageFileInfo['savename'];
                    $oldCover = M('Book')->where(['id'=>I('post.id')])->getField('book_cover');
                }
                if ($bookBannerValidate) {
                    $bannerFileInfo = $this->fileUpload($_FILES['book_banner']);
                    if (!is_array($bannerFileInfo)) {
                        $this->returnCode(0, '文件上传失败！');
                    }
                    $addData['book_banner'] = $bannerFileInfo['savepath'].$bannerFileInfo['savename'];
                    $oldBanner = M('Book')->where(['id'=>I('post.id')])->getField('book_banner');
                }
                M()->startTrans();
                try {
                    $bookInfo = M('Book')->save($addData);
                    if ($bookInfo === false) {
                        M()->rollback();
                        $this->returnCode(0, '修改失败！');
                    }
                    $correlation = M('Book_categories');
                    $oldCategories = $correlation->where(['book_id'=>I('post.id')])->delete();
                    if ($oldCategories === false) {
                        M()->rollback();
                        $this->returnCode(0, '修改失败！');
                    }
                    foreach ($categories as $v) {
                        $addInfo = $correlation->add(['book_id'=>I('post.id'), 'category_id' => $v]);
                        if (!$addInfo) {
                            $this->returnCode(0, '修改失败！');
                        }
                    }
                } catch (\Exception $e) {
                    M()->rollback();
                    $this->returnCode(0, '数据格式错误，修改失败！');
                }
                M()->commit();
                @unlink(PATH_DIR.$oldCover);
                @unlink(PATH_DIR.$oldBanner);
                $this->returnCode(1, '修改成功！', array('bookId'=>$bookInfo));

            } else {
                $this->returnCode(0, '缺少数据，添加失败！');
            }
        }
        if (!empty(I('get.id')) && is_numeric(I('get.id'))) {
            //类别列表
            $categoryList = M('Category')->field('id, category')->select();
            //作者列表
            $authorList = M('Author')->field('id, author')->select();
            $bookInfo = M('Book')->field('book_name,author_id,book_synopsis,book_cover,book_banner,is_hot,number_of_words')->where(['id'=>I('get.id')])->find();
            $bookCategories = M('Book_categories')->where(['book_id'=>I('get.id')])->getField('category_id', true);
            $bookCategories = implode(',', $bookCategories);

            $this->assign('bookCategories', $bookCategories);
            $this->assign('bookInfo', $bookInfo);
            $this->assign('categoryList', $categoryList);
            $this->assign('authorList', $authorList);
            $this->display();
        }
    }
}