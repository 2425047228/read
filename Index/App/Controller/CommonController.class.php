<?php

/**
 * Created by PhpStorm.
 * User: 帅
 * Date: 2017/3/11
 * Time: 9:41
 */
namespace App\Controller;


use Think\Controller;
use Think\Upload;

class CommonController extends EmptyController
{
    private $key = 'read';    //加密key
    protected $token = null;    //数据接收token

    //初始化数据token
    public function __construct()
    {
        parent::__construct();
        $token = I('post.token');
        if (!empty($token)) {
            $this->token = substr($this->keyword_encode($token,"DECODE"), 10);
        }
    }


    //加密信息
    protected function keyword_encode($string, $operation = 'DECODE', $key = '', $expiry = 0)
    {
        // 动态密匙长度，相同的明文会生成不同密文就是依靠动态密匙
        // 加入随机密钥，可以令密文无任何规律，即便是原文和密钥完全相同，加密结果也会每次不同，增大破解难度。
        // 取值越大，密文变动规律越大，密文变化 = 16 的 $ckey_length 次方
        // 当此值为 0 时，则不产生随机密钥
        $ckey_length = 4;

        // 密匙
        // $GLOBALS['discuz_auth_key'] 这里可以根据自己的需要修改 snexy
        //$key = md5($key ? $key : $GLOBALS['discuz_auth_key']);
        $key = md5($key ? $key : $this->key);
        // 密匙a会参与加解密
        $keya = md5(substr($key, 0, 16));
        // 密匙b会用来做数据完整性验证
        $keyb = md5(substr($key, 16, 16));
        // 密匙c用于变化生成的密文
        $keyc = $ckey_length ? ($operation == 'DECODE' ? substr($string, 0, $ckey_length): substr(md5(microtime()), -$ckey_length)) : '';
        // 参与运算的密匙
        $cryptkey = $keya.md5($keya.$keyc);
        $key_length = strlen($cryptkey);
        // 明文，前10位用来保存时间戳，解密时验证数据有效性，10到26位用来保存$keyb(密匙b)，解密时会通过这个密匙验证数据完整性
        // 如果是解码的话，会从第$ckey_length位开始，因为密文前$ckey_length位保存 动态密匙，以保证解密正确
        $string = $operation == 'DECODE' ? base64_decode(substr($string, $ckey_length)) : sprintf('%010d', $expiry ? $expiry + time() : 0).substr(md5($string.$keyb), 0, 16).$string;
        $string_length = strlen($string);
        $result = '';
        $box = range(0, 255);
        $rndkey = array();
        // 产生密匙簿
        for($i = 0; $i <= 255; $i++) {
            $rndkey[$i] = ord($cryptkey[$i % $key_length]);
        }
        // 用固定的算法，打乱密匙簿，增加随机性，好像很复杂，实际上并不会增加密文的强度
        for($j = $i = 0; $i < 256; $i++) {
            $j = ($j + $box[$i] + $rndkey[$i]) % 256;
            $tmp = $box[$i];
            $box[$i] = $box[$j];
            $box[$j] = $tmp;
        }
        // 核心加解密部分
        for($a = $j = $i = 0; $i < $string_length; $i++) {
            $a = ($a + 1) % 256;
            $j = ($j + $box[$a]) % 256;
            $tmp = $box[$a];
            $box[$a] = $box[$j];
            $box[$j] = $tmp;
            // 从密匙簿得出密匙进行异或，再转成字符
            $result .= chr(ord($string[$i]) ^ ($box[($box[$a] + $box[$j]) % 256]));
        }
        if($operation == 'DECODE') {
            // substr($result, 0, 10) == 0 验证数据有效性
            // substr($result, 0, 10) - time() > 0 验证数据有效性
            // substr($result, 10, 16) == substr(md5(substr($result, 26).$keyb), 0, 16) 验证数据完整性
            // 验证数据有效性，请看未加密明文的格式
            if((substr($result, 0, 10) == 0 || substr($result, 0, 10) - time() > 0) && substr($result, 10, 16) == substr(md5(substr($result, 26).$keyb), 0, 16)) {
                return substr($result, 26);
            } else {
                return '';
            }
        } else {
            // 把动态密匙保存在密文里，这也是为什么同样的明文，生产不同密文后能解密的原因
            // 因为加密后的密文可能是一些特殊字符，复制过程可能会丢失，所以用base64编码
            return $keyc.str_replace('=', '', base64_encode($result));
        }
    }


    //分页数据方法
    protected function page($page = 0, $limit = 10)
    {
        //$limit:取出记录数;$page:页数;
        $limit = !empty($limit) ? $limit : 10;
        $page = !empty($page) ? (($page-1) * $limit) : 0;
        return ['page'=>$page,'limit'=>$limit];    //返回数组
    }

    //检查数据方法
    protected function checkData($data = array())
    {
        if (!empty($data) && is_array($data)) {
            return $data;
        }
        return array();
    }

    //多维数组变单维
    protected function changeArray($arr = array(), $key)
    {
        if (is_array($arr) && !empty($key)) {
            $newArray = array();
            foreach ($arr as $v) {
                array_push($newArray,$v[$key]);
            }
            return $newArray;
        }
        return array();
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
        $directory = PATH_DIR.$savePath;
        if (!file_exists($directory)) {
            $mkInfo = mkdir($directory, 0777, true);
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


    //生成上传文件url方法
    protected function setUploadFileUrl($fileInfoArray)
    {
        return 'http://'.$_SERVER['HTTP_HOST'].$fileInfoArray['savepath'].$fileInfoArray['savename'];
    }

    //发送验证码方法
    protected function sendCode($mobile = '默认网站管理员的手机号'){
        $apikey = "9b70d20995000236663c521a092ac776"; //修改为您的apikey(https://www.yunpian.com)登陆官网后获取
        $chars = "0123456789";
        $str = "";
        for ($i = 0; $i < 4; $i++) {
            $str .= substr($chars, mt_rand(0, strlen($chars) - 1), 1);
        }
        $text="【诗奈尔洗护】您的验证码是".$str;

        $data=array('text'=>$text,'apikey'=>$apikey,'mobile'=>$mobile);
        //发送数据
        $ch = curl_init("https://sms.yunpian.com/v1/sms/send.json");
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
        curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (compatible; MSIE 5.01; Windows NT 5.0)');
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($ch, CURLOPT_AUTOREFERER, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $tmpInfo = curl_exec($ch);
        if (curl_errno($ch)) {
            return curl_error($ch);
        }
        curl_close($ch);
        $array = json_decode($tmpInfo, true);
        if($array['code'] == 0 && $array['msg'] == "OK"){
            $array['validateCode'] = $str;
        }else{
            $array['validateCode'] = false;
        }
        return $array;

    }


}