<?php

namespace Admin\Controller;
use Think\Controller;
class LoginController extends Controller 
{
    public function login(){
        if(IS_POST){
            $model = D('Admin');
            if($model->validate($model->_login_validate)->create('',7)){//1代表添加，2是修改，其他值随便
                if(TRUE === $model->login())
                redirect(U('Admin/Index/index'));//不用$this这个可以直接跳转不提示
            }
           
            $this->error($model->getError());
        }
        $this->display();
    }
    //生成验证码
    public function chkcode(){
        $verify = new \Think\Verify(array(
            'length' => 4,
            'useNoise' => FALSE,
            'fontSize'=>15,
            'imageW'=>146,
            'imageH'=>30,
            'useCurve'=>false,
        ));
        $verify->entry();
        
    }
     public function logout(){
        session('id', null);
        redirect(U('Admin/Login/login'));
    }

   
}
