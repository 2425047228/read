<?php
/**
 * Created by PhpStorm.
 * User: 帅
 * Date: 2017/3/14
 * Time: 14:29
 */

namespace App\Controller;


use Think\Controller;

class EmptyController extends Controller
{
    public function _empty()
    {
        $this->returnCode('接口不存在！');
    }

    //返回成功或失败的信息
    protected function returnInformation($retcode, $status, $data = null, $other = [], $superaddition = [])
    {
        //返回格式为    {retcode:'', status:'', {other_key:other_value}, data:'', {superaddition_key:superaddition_value}}
        $arr = ['retcode' => (string)$retcode, 'status' => $status];
        if (isset($other) && is_array($other)) {
            foreach ($other as $k => $v) {
                $arr[$k] = $v;
            }
        }
        if (isset($data) && is_array($data)) {
            $arr['data'] = $data;
        }

        if (isset($superaddition) && is_array($superaddition)) {
            foreach ($superaddition as $k => $v) {
                $arr[$k] = $v;
            }
        }
        exit(json_encode($arr));
    }

    //返回码方法
    protected function returnCode($code)
    {
        if (!is_numeric($code)) {
            $this->returnInformation(999,$code);
        }
        switch ($code)
        {
            case 0:
                $this->returnInformation(0,'OK');
                break;
            case 1:
                $this->returnInformation(1,'参数错误！');
                break;
            case 2:
                $this->returnInformation(2,'非法访问！');
                break;
            case 3:
                $this->returnInformation(3,'FAIL');
                break;
            case 4:
                $this->returnInformation(4,'token不存在！');
                break;
        }
    }
}