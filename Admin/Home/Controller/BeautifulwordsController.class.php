<?php
/**
 * Created by PhpStorm.
 * User: 帅
 * Date: 2017/3/4
 * Time: 10:39
 */

namespace Home\Controller;


class BeautifulwordsController extends CommonController
{
    //美文列表
    public function w_list()
    {
        $beautifulWords = M('Beautiful_words');
        //美文删除
        if (!empty(I('post.id')) && is_numeric(I('post.id'))) {
            $file = $beautifulWords->field('cover, audio')->where(['id' => I('post.id')])->find();
            //删除文件
            @unlink(PATH_DIR.$file['cover']);
            @unlink(PATH_DIR.$file['audio']);
            $delInfo = $beautifulWords->where(['id'=>I('post.id')])->delete();
            if ($delInfo) {
                exit('SUCCESS');
            }
            exit('FAIL');
        }
        $beautifulWordsList = $beautifulWords
            ->field('id, title, author, cover, url, sent_time')
            ->order('id desc')
            ->select();
        $this->assign('beautifulWordsList', $beautifulWordsList);
        $this->display();
    }
    
    //上传美文
    public function w_add()
    {
        if (IS_POST) {
            $validateFile = $_FILES['cover']['error'] == 0 && $_FILES['cover']['size'] > 0;    //判断封面上传
            $validateInfo = !empty(I('post.title')) && !empty(I('post.author'));    //判断信息上传
            $validateAudio = $_FILES['audio']['error'] == 0 && $_FILES['audio']['size'] > 0;    //判断音频上传
            //判断内容输出方式及内容上传
            $validateEditor = false;
            switch (I('post.editor_way'))
            {
                case 0:
                    $validateEditor = !empty(I('post.content'));
                    break;
                case 1:
                    $validateEditor = !empty(I('post.url'));
                    break;
            }
            if ($validateFile && $validateInfo && $validateEditor) {
                //罗列添加数据
                $data['title'] = I('post.title');
                $data['author'] = I('post.author');
                $data['editor_way'] = I('post.editor_way');
                $data['content'] = I('post.content');
                $data['url'] = I('post.url');
                $data['sent_time'] = time();
                $cover = $this->fileUpload($_FILES['cover']);
                if(!is_array($cover)) {    //判断封面是否上传成功
                    return $this->error($cover);
                }
                $data['cover'] = $cover['savepath'].$cover['savename'];
                if ($validateAudio) {    //判断是否有音频上传
                    $audio = $this->fileUpload($_FILES['audio'], true, array('mp3'), 'audios/');
                    if (!is_array($audio)) {    //判断音频是否上传成功
                        return $this->error($audio);
                    }
                    $data['audio'] = $audio['savepath'].$audio['savename'];
                }
                try{
                    $addInfo = M('Beautiful_words')->add($data);
                    if (!$addInfo) {
                        return $this->error('添加失败！');
                    }
                    return $this->redirect('Beautifulwords/w_list');
                }catch (\Exception $e){
                    return $this->error('添加失败！');
                }
            } else {
                return $this->error('添加失败！');
            }
        }

        $this->assign('url', C('ARTICLE_STATIC_FILE'));
        $this->display();
    }
    
}