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
     * @return 文件上传信息
     */
    protected function fileUpload($fileName, $uploadType = true, $fileType = array('jpg', 'gif', 'png', 'jpeg'))
    {
        $year = date('Y');    //年
        $month = date('m');    //月
        $day = date('d');    //日
        $savePath = "/Upload/{$year}/{$month}/{$day}/";
        //$savePath = str_replace(DIRECTORY_SEPARATOR, '/', $systemSavePath);
        if (!file_exists($savePath)) {
            $mkInfo = mkdir($savePath, 777, true);
            if (!$mkInfo) {    //创建失败时处理
                return $this->error("{$savePath}目录创建失败!");
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
            return $this->error($uploader->getError());
        }

    }
}