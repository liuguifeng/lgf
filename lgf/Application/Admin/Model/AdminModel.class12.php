<?php
namespace Admin\Model;
use Think\Model;
class AdminModel extends Model{
    //登录时表单验证规则
    public $_login_validate = array(//在控制器中已经login_validate
            array('username','require','用户名不能为空！',1),
            array('password','require','密码不能为空！',1),
            array('chkcode','require','验证码不能为空！',1),
            array('chkcode','chk_chkcode','验证码不正确！',1,'callback'),
           
        );
    //添加和修改管理员时 的规则
    //public $_validate = array();
    public function chk_chkcode($code){
        $verify = new \Think\Verify();
        return $verify->check($code);
    }
    public function login(){
        //登录表单验证规则
        //获取表单的用户密码
        $username = $this->username;
        $password = $this->password;
        //先查询数据库有木有这个账号
        $user = $this->where(array(
            'username' => array('eq',$username),
        ))->find();
        //判断有木有账号
        if($user){
            //判断是否启用
            if($user['id'] == 1 || $user['is_use']==1){
                //判断密码
                if($user['password']==  md5($password.C('MD5_KEY'))){
                    session('id',$user['id']);
                    session('username',$user['username']);
                    return true;
                }else{
                    $this->error = '密码不正确';
                }
                
            }else{
                $this->error = '账号被禁用';
            }
        }else{
            $this->error = '用户名不存在';
        }
    }
}
