<?php
/**
 * Created by PhpStorm.
 * User: 帅
 * Date: 2017/3/2
 * Time: 19:55
 */

namespace Home\Controller;


class MembersController extends CommonController
{
    //爱好管理
    public function hobby()
    {
        $hobbyList = M('Hobby')->select();
        $this->assign('hobbyList', $hobbyList);
        $this->display();
    }
    
    //添加爱好
    public function h_add()
    {
        if (!empty(I('post.hobby'))) {
            try{
                $hobbyInfo = M('Hobby')->add(['hobby'=>I('post.hobby')]);
                if ($hobbyInfo) {
                    return $this->redirect('Members/hobby');
                }
                return $this->error('添加失败！');
            }catch (\Exception $e){
                return $this->error('添加失败！');
            }
        }
        $this->display();
    }

    //修改爱好
    public function h_update()
    {
        $vaildate = !empty(I('post.hobby')) && !empty(I('post.id'));
        if ($vaildate) {
            try{
                $updateInfo = M('Hobby')->save([
                    'id' => I('post.id'),
                    'hobby' => I('post.hobby'),
                ]);
                if ($updateInfo !== false) {
                    return $this->redirect('Members/hobby');
                }
                return $this->error('修改失败！');
            }catch (\Exception $e) {
                return $this->error('修改失败！');
            }
        }
        $hobby = M('Hobby')->where(['id' => I('get.id')])->find();
        $this->assign('hobby', $hobby);
        $this->display();
    }
}