<?php
namespace Admin\Model;
use Think\Model;
class AdminModel extends Model 
{
    protected $insertFields = array('username','password','cpassword','is_use');//允许的字段
    protected $updateFields = array('id','username','cpassword','is_use','password');
    public $_login_validate = array(//在控制器中已经login_validate
        array('username','require','用户名不能为空！',1),
        array('password','require','密码不能为空！',1),
        array('chkcode','require','验证码不能为空！',1),
        array('chkcode','chk_chkcode','验证码不正确！',1,'callback'),

    );
    protected $_validate = array(
            array('username', 'require', '账号不能为空！', 1, 'regex', 3),
            array('username', '1,30', '账号的值最长不能超过 30 个字符！', 1, 'length', 3),
            array('password', 'require', '密码不能为空！', 1, 'regex', 1),//第六个参数代表什么时候验证，1：添加，2:修改，3：所有情况
            //array('password', '1,32', '密码的值最长不能超过 32 个字符！', 1, 'length', 3),
            array('cpassword', 'password', '确认密码不对', 1, 'confirm', 3),
            array('is_use', 'number', '是否启用 1：启用0：禁用必须是一个整数！', 2, 'regex', 3),
            array('username', '', '账号已经存在', 1, 'unique', 3),
	);
    //验证验证码
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
        //
        //判断有木有账号
        if($user){
            //判断是否启用
            if($user['id'] == 1 || $user['is_use']==1){
                //判断密码
                if($user['password']==  md5($password)){
                    session('id',$user['id']);
                    session('username',$user['username']);
                    return TRUE;
                }else{
                    //var_dump($_POST['password']);die();
                    $this->error = '密码不正确'; 
                    return FALSE;
                    
                }
                
            }else{
                $this->error = '账号被禁用';
                return FALSE;
            }
        }else{
            $this->error = '用户名不存在';
            return FALSE;
        }
    }
    public function search($pageSize = 20)
    {
        /**************************************** 搜索 ****************************************/
        $where = array();
        if($username = I('get.username'))
                $where['username'] = array('like', "%$username%");
        $is_use = I('get.is_use');
        if($is_use != '' && $is_use != '-1')
                $where['is_use'] = array('eq', $is_use);
        /************************************* 翻页 ****************************************/
        $count = $this->alias('a')->where($where)->count();
        $page = new \Think\Page($count, $pageSize);
        // 配置翻页的样式
        $page->setConfig('prev', '上一页');
        $page->setConfig('next', '下一页');
        $data['page'] = $page->show();
        /************************************** 取数据 ******************************************/
        $data['data'] = $this->alias('a')->where($where)->group('a.id')->limit($page->firstRow.','.$page->listRows)->select();
        return $data;
    }
        
	// 添加前
    protected function _before_insert(&$data, $option)
    {
        $data['password'] = md5($data['password']);
    }
    protected function _after_insert($data, $option)
    {
       $roleId = I('post.role_id');
            if($roleId)
            {
                    $arModel = M('AdminRole');
                    foreach ($roleId as $v)
                    {
                            $arModel->add(array(
                                    'admin_id' => $data['id'],
                                    'role_id' => $v,
                            ));
                    }
            }
    }
    // 修改前
    protected function _before_update(&$data, $option)
    {   
            	// 如果是超级管理员必须是启用的
            if($option['where']['id'] == 1)
                    $data['is_use'] = 1;         // 直接设置为启用状态
            $roleId = I('post.role_id');
        // 先清除原来的数据
            $arModel = M('AdminRole');
            $arModel->where(array('admin_id'=>array('eq', $option['where']['id'])))->delete();
            if($roleId)
            {
                    foreach ($roleId as $v)
                    {
                            $arModel->add(array(
                                    'admin_id' => $option['where']['id'],
                                    'role_id' => $v,
                            ));
                    }
            }
        // 判断密码为空就不修改这个字段
        if(empty($data['password']))
                unset($data['password']);
        else 
                $data['password'] = md5($data['password'] . C('MD5_KEY'));
    }
    // 删除前
    protected function _before_delete($option)
    {           
            if($option['where']['id'] == 1)
		{
			$this->error = '超级管理员不能被删除！';
			return FALSE;
		}
            // 在删除admin表中管理员的信息之前先删除admin_role表中这个管理员对应的数据s
            $arModel = M('AdminRole');
            $arModel->where(array('admin_id'=>array('eq', $option['where']['id'])))->delete();
    }
	/************************************ 其他方法 ********************************************/
}