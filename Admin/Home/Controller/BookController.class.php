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
            //判断数据是否有缺失
            if ($validate && $isHotValidate && $bookFileValidate && $bookCoverValidate) {
                $txtFileInfo = $this->fileUpload($_FILES['book_file'], true, array('txt'), 'txt/');
                $imageFileInfo = $this->fileUpload($_FILES['book_cover']);
                if (!is_array($txtFileInfo) || !is_array($imageFileInfo)) {
                    $this->returnCode(0, '文件上传失败！');
                }
                $bookFile = $txtFileInfo['savepath'].$txtFileInfo['savename'];
                $bookCover = $imageFileInfo['savepath'].$imageFileInfo['savename'];
                $isHot = $isHotValidate == 1 ? I('post.is_hot') : 0;    //是否为最火
                $categoryIds = implode(',', I('post.category_ids'));
                try {
                    $bookInfo = M('Book')->add([
                        'book_name' => I('post.book_name'),
                        'author_id' => I('post.author_id'),
                        'book_synopsis' => I('post.book_synopsis'),
                        'book_cover' => $bookCover,
                        'category_ids' => $categoryIds,
                        'is_hot' => $isHot,
                        'book_file' => $bookFile,
                        'number_of_words' => I('post.number_of_words'),
                        'shelves_time' => time(),
                    ]);
                    if (!$bookInfo) {
                        $this->returnCode(0, '添加失败！');
                    }
                } catch (\Exception $e) {
                    M()->rollback();
                    $this->returnCode(0, '数据格式错误，添加失败！');
                }
                M()->commit();
                $this->sendRequest(array('bookId' => $bookInfo, 'bookFile' => $bookFile));
                $this->returnCode(1, '添加成功！');
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
}