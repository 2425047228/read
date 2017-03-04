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
        $beautifulWordsList = M('Beautiful_words')
            ->field('id, title, author, cover, content, url, audio, sent_time')
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
                $data['content'] = htmlspecialchars(I('post.content'));
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
        $uri = U('w_editor_upload');
        $this->assign('url', "http://{$_SERVER['HTTP_HOST']}$uri");
        $this->display();
    }

    //文章文件上传
    public function w_editor_upload()
    {
        $returnInfo = array(
            "state" => "没有文件被上传",    //上传状态信息
            "url" => '',    //返回文件全路径
            "title" => '',    //新文件名
            "original" => '',    //原始文件名
            "type" => '',    //文件类型
            "size" => 0,    //文件大小
        );
        if ($_FILES['upfile']['error'] == 0 && $_FILES['upfile']['size'] > 0) {
            $fileInfo = $_FILES['upfile'];
            $upFile = $this->fileUpload($fileInfo);
            $returnInfo['original'] = $fileInfo['name'];
            $returnInfo['type'] = $fileInfo['type'];
            $returnInfo['size'] = $fileInfo['size'];
            if (!is_array($upFile)) {
                $returnInfo['state'] = $upFile;
                exit(json_encode($returnInfo));
            }
            $returnInfo['state'] = 'SUCCESS';
            $returnInfo['url'] = $upFile['savepath'].$upFile['savename'];
            $returnInfo['title'] = $upFile['savename'];
            exit(json_encode($returnInfo));
        }
            /*return array(
                "state" => $this->stateInfo,
                "url" => $this->fullName,
                "title" => $this->fileName,
                "original" => $this->oriName,
                "type" => $this->fileType,
                "size" => $this->fileSize
            )*/
            /*private $stateMap = array( //上传状态映射表，国际化用户需考虑此处数据的国际化
        "SUCCESS", //上传成功标记，在UEditor中内不可改变，否则flash判断会出错
        "文件大小超出 upload_max_filesize 限制",
        "文件大小超出 MAX_FILE_SIZE 限制",
        "文件未被完整上传",
        "没有文件被上传",
        "上传文件为空",
        "ERROR_TMP_FILE" => "临时文件错误",
        "ERROR_TMP_FILE_NOT_FOUND" => "找不到临时文件",
        "ERROR_SIZE_EXCEED" => "文件大小超出网站限制",
        "ERROR_TYPE_NOT_ALLOWED" => "文件类型不允许",
        "ERROR_CREATE_DIR" => "目录创建失败",
        "ERROR_DIR_NOT_WRITEABLE" => "目录没有写权限",
        "ERROR_FILE_MOVE" => "文件保存时出错",
        "ERROR_FILE_NOT_FOUND" => "找不到上传文件",
        "ERROR_WRITE_CONTENT" => "写入文件内容错误",
        "ERROR_UNKNOWN" => "未知错误",
        "ERROR_DEAD_LINK" => "链接不可用",
        "ERROR_HTTP_LINK" => "链接不是http链接",
        "ERROR_HTTP_CONTENTTYPE" => "链接contentType不正确",
        "INVALID_URL" => "非法 URL",
        "INVALID_IP" => "非法 IP"
    );*/

    }
}