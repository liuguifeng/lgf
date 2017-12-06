<?php
namespace Admin\Model;
use Think\Model;
class MemberModel extends Model {
    // 注册时表单中允许提交的字符
    protected $insertFields = array( 'password','mobile','addtime','status');
    // 登录时使用的表单验证规则
    public $_login_validate = array(

            array('password', '6,20', '密码必须是6-20位的字符！', 1, 'length'),
    );
    // 注册时的表单验证规则
    protected $_validate = array(
        array('mobile', '', 'mobile已经被注册过了！', 1, 'unique'),
        array('mobile', 'require', '手机不能为空！', 1, 'regex',3),
        array('mobile', 'number', '手机号码必须是数字！', 2, 'regex',3),
        array('mobile', '11,11', '手机号码必须是11位的字符！', 1, 'length'),
        array('status', 'number', 'status必须是数字！', 2, 'regex',3),
        array('password', 'require', '密码不能为空！', 1, 'regex',1),
        array('password', '6,20', '密码必须是6-20位的字符！', 1, 'length',1),
	);
        //后台搜索
    public function search($pageSize = 10) {
        /*         * ************************************** 搜索 *************************************** */
        if ($mobile = I('get.mobile'))
            $where['mobile'] = array('like', "%$mobile%");
        $addtimefrom = I('get.addtimefrom');
        $addtimeto = I('get.addtimeto');
        if ($addtimefrom && $addtimeto)
            $where['addtime'] = array('between', array(strtotime("$addtimefrom 00:00:00"), strtotime("$addtimeto 23:59:59")));
        elseif ($addtimefrom)
            $where['addtime'] = array('egt', strtotime("$addtimefrom 00:00:00"));
        elseif ($addtimeto)
            $where['addtime'] = array('elt', strtotime("$addtimeto 23:59:59"));
        /*         * *********************************** 翻页 *************************************** */
        $count = $this->alias('a')->where($where)->count();
        $page = new \Think\Page($count, $pageSize);
        // 配置翻页的样式
        $page->setConfig('prev', '上一页');
        $page->setConfig('next', '下一页');
        $data['page'] = $page->show();
        /*         * ************************************ 取数据 ***************************************** */
        $data['data'] = $this->where($where)->order('id desc')->limit($page->firstRow . ',' . $page->listRows)->select();
        return $data;
    }
    public function chk_chkcode($code)
    {
             $verify = new \Think\Verify();
             return $verify->check($code);
    }
    // 在会员记录插入到数据库之前
    protected function _before_insert(&$data, $option)
    {
            $data['addtime'] = time();  // 注册的当前时间
            // 先把会员的密码加密
            $data['password'] = md5($data['password'].C('MD5_KEY'));
    }
    // 在会员注册成功之后
    protected function _after_insert($data, $option)
    {
    }
    protected function _before_update(&$data, $option){
        if(empty($data['password']))
            unset($data['password']);
        else 
            $data['password'] = md5($data['password'] . C('MD5_KEY'));
    }
    protected function _before_delete($option) {
        if (is_array($option['where']['id'])) {
            $this->error = '不支持批量删除';
            return FALSE;
        }
        $where['member_id']=array("eq",$option['where']['id']);
        $scModel=D("Admin/Collection");
        $dzModel=D("Admin/Favourite");
        $scModel->where($where)->delete();//删除会员后把会员的收藏和点赞记录删除
        $dzModel->where($where)->delete();
    }

        public function login(){
        $password=I('post.password');
        $mobile=I('post.mobile');
        if(!$password||!$mobile){
            $this->error = array(4001=>'账号或密码不能为空！');
            return FALSE;
        }
            //$password=$this->password;
        $user=$this->where(array('mobile'=>array('eq',$mobile)))->find();
        if($user){
            // 判断账号是否已经启用
            if($user['status']==1)
            {
                    // 判断密码是否正确
                if($password)
                {
                    if($user['password'] != md5($password.C('MD5_KEY'))){
                        $this->error = array(4008=>'密码不正确！');
                        return FALSE;	
                    }
                }
                if(empty($user['token'])){
                    $model=D('Admin/Member');
                    $token=md5(time().mt_rand());
                    $user1['token']=$token;
                    $user1['outofday']= time()+3600*24*30;    
                    $where['id']= array('eq',$user['id']);
                    //$res=$model->where($where)->setField($user1);
                    $res=$model->where($where)->save($user1);
                    if($res!==false){
                        $users=$this->where(array('mobile'=>array('eq',$mobile)))->find();
                        $data['id']=$users['id'];
                        $data['token']=$users['token'];
                        $data['mobile']=$users['mobile'];
                        return $data;
                    }
                    else{
                        $this->error=array(4009=>"登录异常，请重新登录");
                        return FALSE;
                    }
                }else{
                    $data['id']=$user['id'];
                    $data['token']=$user['token'];
                    $data['mobile']=$user['mobile'];
                    return $data;
                }   
            }
            else 
            {
                $this->error =array(4010=> '账号被禁用！');
                return FALSE;
            }
        }
        else 
        {
            $this->error =array(4011=> '账号不存在！') ;
            return FALSE;
        }
    }
    public function getInformation(){
        $token=I('post.token');
        $id=I('post.user_id');
        if(!$id||!$token){
            $this->error=array(4012=>"获取信息失败，请重新登录");
            return false;
        }
        $model=D('Admin/Member');
        $data=$model->where(array('id'=>array('eq',$id)))->find();
        if($data['token']==$token){
            $scModel=D('Admin/Collection');
            $scdata=$scModel->field('c.*')->alias('a')->join("left join php34_member b on a.member_id=b.id left join php34_movie c on c.id=a.movie_id where b.id=$id")->order('id desc')->select();
            $scdatas['colect']=$scdata;
            return $scdatas;
        }else{
            $this->error=array(4012=>"获取信息失败，请重新登录");
            return false;
        }
    }
    public function logout(){
        $token=I('post.token');
        $id=I('post.user_id');
        if(!$id||!$token){
            $this->error=array(4020=>"非法操作");
            return false;
        }
        $model=D('Admin/Member');
        $data=$model->where(array('id'=>array('eq',$id)))->find();
        if($data['token']==$token){
            $where['id']= array('eq',$id);
                $user['token']='';
            $res=$model->where($where)->setField($user);
                if($res!==false){
                        return true;
                }
        }else{
            $this->error=array(4020=>"非法操作");
            return false;
        }
		
    }
    //忘记密码
    public function editPassword(){
            $bmobSms = new \Lib\api\bmob\lib\BmobSms;
            $data['password']=I('post.password');
            $data['cpassword']=I('post.cpassword');
            $data['mobile']=I('post.mobile');
            $smsid=I('post.smsid');
            $regid=I('post.regid');
            if(!$data['password']||!$data['mobile']){
                $this->error=array(4001=> '手机或者密码不能为空');
                return false;
            }
            if(!$regid){
                $this->error=array(4002=> '短信验证码已不能为空');
                return false;
            }
            if($data['password']!=$data['cpassword']){
                $this->error=array(4027=>"两次密码不一致");
                return false;
            }
            if(!$smsid){
                $this->error=array(4003=> '短信发送失败');
                return false;
            }
            $res = $bmobSms->querySms($smsid);
            if($res->verify_state==true&&$res->sms_state=='SUCCESS'){
                $this->error=array(4005=> '短信验证码已失效');
                 return false;
            }
            $res = $bmobSms->verifySmsCode($data['mobile'],$regid);
            if($res->msg != 'ok'){
                $this->error=array(4004=> '短信验证失败');
                return false;
            }
            $model=D("Admin/Member");
            $where['mobile']=array("eq",$data["mobile"]);
            $memberdata=$model->where($where)->find();
            if($memberdata){
                $newuser['password']=md5($data['password'].C('MD5_KEY'));
                $newuser['token']='';
                $res=$model->where($where)->setField($newuser);
                if($res!==false){
                    
                    return true;
                }
            }
            else{
                $this->error=array(4011=> '账号不存在');
                return false;
            }
    }

 
}

