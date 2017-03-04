<?php
/**
 * Created by PhpStorm.
 * 公共控制器
 * User: 帅
 * Date: 2017/3/1
 * Time: 21:00
 */

namespace Home\Controller;


use Think\Controller;
use Think\Upload;

class CommonController extends EmptyController
{
    public function __construct()
    {
        parent::__construct();
        if (empty(session('validate')) || session('validate') !== 'SUCCESS') {    //初始化判断是否已登陆
            $url = U('Login/login');
            exit("<script>window.top.location.href='{$url}';</script>");
        }
    }

    /**
     *文件上传方法
     * @param String $fileName $_FILE文件对应值
     * @param String $uploadType true为单文件上传，false为多文件上传
     * @param Array $fileType 允许上传文件的类型
     * @param String $branchDirectory 上传文件的分支路径
     * @return 文件上传信息
     */
    protected function fileUpload($fileName, $uploadType = true, $fileType = array('jpg', 'gif', 'png', 'jpeg'), $branchDirectory = 'images/')
    {
        $year = date('Y');    //年
        $month = date('m');    //月
        $day = date('d');    //日
        $savePath = "/Upload/{$branchDirectory}{$year}/{$month}/{$day}/";
        //$savePath = str_replace(DIRECTORY_SEPARATOR, '/', $systemSavePath);
        if (!file_exists($savePath)) {
            $mkInfo = mkdir($savePath, 777, true);
            if (!$mkInfo) {    //创建失败时处理
                return "{$savePath}目录创建失败!";
            }
        }
        $uploader = new Upload();
        $uploader->rootPath = PATH_DIR;    //设置上传文件根目录
        $uploader->exts = $fileType;
        $uploader->saveName = array('uniqid','');
        $uploader->savePath = $savePath;    //设置保存文件路径
        $uploader->autoSub = false;    //不使用子目录保存
        if ($uploadType) {
            $info = $uploader->uploadOne($fileName);
        } else {
            $info = $uploader->upload();
        }
        if ($info) {
            return $info;
        } else {
            return $uploader->getError();
        }

    }

    /**
     * json返回数据码方法
     * @param Int $code 返回码，1 为成功，其他失败
     * @param String $msg 返回信息
     * @param Bool $isPrint 是否直接结束脚本并输出
     * @return json格式字符串
     */
    protected function returnCode($code, $msg, $isPrint = true)
    {
        $jsonArray = array('code' => $code, 'msg' => $msg);
        $returnInfo = json_encode($jsonArray);
        if ($isPrint) {
            exit($returnInfo);
        }
        return $returnInfo;
    }

    protected function sendRequest($data = array())
    {

        $uri = U('Chapter/chapter');
        $ch = curl_init("http://{$_SERVER['HTTP_HOST']}$uri");
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_SAFE_UPLOAD, false);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_exec($ch);
    }

    //文章文件上传
    public function article_static_upload()
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
    }
}