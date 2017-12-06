<?php
namespace Home\Controller;
class AppController extends BaseController 
{
    //static $json=-+-
    public function index(){
        
        $json=new \Lib\api\ResponseJson;
        $movie=D('Admin/Movie');
        //热点视频
        $hotmovie=$movie->getHot();
        //最新视频
        $newmovie=$movie->getNew();
        //推荐视频
        $bestmovie=$movie->getBest();
        $advertModel=D("Admin/Advert");
        $advdata=$advertModel->getAdverts();
        //var_dump($bestmovie);
        $allarr=array();
        $allarr['is_hot']=$hotmovie;
        $allarr['is_new']=$newmovie;
        $allarr['is_best']=$bestmovie;
        $allarr['homeadvert']=$advdata;
        $json->xmljson(200,'success',$allarr,'json');
    }
    public function ishot(){
        
        $json=new \Lib\api\ResponseJson;
        $movie=D('Admin/Movie');
        //热点视频
        $hotmovie=$movie->getHot();
        //最新视频
        $json->xmljson(200,'success',$hotmovie,'json');
    }
    public function isnew(){
        
        $json=new \Lib\api\ResponseJson;
        $movie=D('Admin/Movie');
        //热点视频
        $newmovie=$movie->getNew();
        //最新视频
        $json->xmljson(200,'success',$newmovie,'json');
    }
    public function isbest(){
        
        $json=new \Lib\api\ResponseJson;
        $movie=D('Admin/Movie');
        //热点视频
        $bestmovie=$movie->getBest();
        //最新视频
        $json->xmljson(200,'success',$bestmovie,'json');
    }
    	public function morehot(){
        
        $json=new \Lib\api\ResponseJson;
        $movie=D('Admin/Movie');
        //热点视频
        $hotmovie=$movie->getHots();
        $json->xmljson(200,'success',$hotmovie,'json');
    }
    public function morenew(){
        
        $json=new \Lib\api\ResponseJson;
        $movie=D('Admin/Movie');
        $newmovie=$movie->getNews();
        $json->xmljson(200,'success',$newmovie,'json');
    }
    public function morebest(){
        
        $json=new \Lib\api\ResponseJson;
        $movie=D('Admin/Movie');
        $bestmovie=$movie->getBests();
        $json->xmljson(200,'success',$bestmovie,'json');
    }
    
    public function registers(){
        $json=new \Lib\api\ResponseJson;
        $bmobSms = new \Lib\api\bmob\lib\BmobSms;
        //$bmob=new \Lib\api\bmob\BmobApi;
		
        if (IS_POST) {
//            $username=I('post.username');
            $data['password']=I('post.password');
            $data['mobile']=I('post.mobile');
            $smsid=I('post.smsid');
            $regid=I('post.regid');
            if(!$data['password']||!$data['mobile']){
                $json->xmljson(4001,'手机或者密码不能为空','','json');
            }
            if(!$regid){
                $json->xmljson(4002,'短信验证码不能为空','','json');
            }
            if(!$smsid){
                $json->xmljson(4003, '短信发送失败', '','json');
            }
            $res = $bmobSms->querySms($smsid);
            if($res->verify_state==true&&$res->sms_state=='SUCCESS'){
                    $json->xmljson(4005, '短信验证码已失效', '','json');
            }
            $res = $bmobSms->verifySmsCode($data['mobile'],$regid);
//            if($res->msg = 'ok'){
//                $json->xmljson(4004, '短信验证码正确', '','json');
//            }
            if($res->msg != 'ok'){
                $json->xmljson(4004, '短信验证失败', '','json');
            }
            $model = D('Admin/Member');
            if ($model->create(I('post.'), 1)){
                    if ($model->add($data)) {
                            $json->xmljson(200,'注册成功','','json');

                    }
            }
            
            $json->xmljson(400,$model->getError(),'','json');
        }
    }
    public function login(){
        $json=new \Lib\api\ResponseJson;
        if (IS_POST) {
            $password=I('post.password');
            $mobile=I('post.mobile');
            if(!$password||!$mobile){
                 $json->xmljson(4001,'手机或者密码不能为空','','json');
            }
            $model = D('Admin/Member');
            if ($model->validate($model->_login_validate)->create(I('post.'), 9)) {
                if ($user=$model->login()) {
                    $json->xmljson(200,'登陆成功',$user,'json');
                }
            }
            if($model->getError()&&is_array($model->getError())){
                foreach ($model->getError() as $k => $v){
                        $json->xmljson($k,$v,'','json');
                }
            }
            $json->xmljson(400,$model->getError(),'','json');
            
        }
    }
    //收藏功能
    function  collect($userid,$movieid){
        
    }
    //取音乐的数据
    public function getyy(){
        $json=new \Lib\api\ResponseJson;
        $catModel=D("Admin/Movie");
        $yydata=$catModel->getCats("音乐");
        //var_dump($yydata);
        //$arr=array();
        $json->xmljson(200,'请求成功',$yydata,'json');
    }
    //取舞蹈的数据
    public function getwd(){
        $json=new \Lib\api\ResponseJson;
        $catModel=D("Admin/Movie");
        $wddata=$catModel->getCats("舞蹈");
        $json->xmljson(200,'请求成功',$wddata,'json');
    }
    //取声乐的数据
    public function getsy(){
        $json=new \Lib\api\ResponseJson;
        $catModel=D("Admin/Movie");
        $sydata=$catModel->getCats("声乐");
        $json->xmljson(200,'请求成功',$sydata,'json');
    }
    public function getyu(){
         //取语言的数据
        $json=new \Lib\api\ResponseJson;
        $catModel=D("Admin/Movie");
        $yudata=$catModel->getCats("语言");
        $json->xmljson(200,'请求成功',$yudata,'json');
    }
    public function getyq(){
         //取乐器的数据
        $json=new \Lib\api\ResponseJson;
        $catModel=D("Admin/Movie");
        $yqdata=$catModel->getCats("乐器");
        $json->xmljson(200,'请求成功',$yqdata,'json');
    }
    public function getother(){
         //取其他的数据
        $json=new \Lib\api\ResponseJson;
        $catModel=D("Admin/Movie");
        $otherdata=$catModel->getCats("其他");
        $json->xmljson(200,'请求成功',$otherdata,'json');
    }
    public function getjj(){
         //取竞技的数据
        $json=new \Lib\api\ResponseJson;
        $catModel=D("Admin/Movie");
        $arr=$catModel->getCats("竞技");
        $json->xmljson(200,'请求成功',$arr,'json');
    }
    //少儿星光大道
    public function getstars(){
        $json=new \Lib\api\ResponseJson;
        $typeModel=D("Admin/Movie");
        $startdata=$typeModel->getFirst('少儿星光大道');
        $json->xmljson(200,'请求成功',$startdata,'json');
    }
    //亚洲星风采
    public function getmimen(){
        $json=new \Lib\api\ResponseJson;
        $typeModel=D("Admin/Movie");
        $startdata=$typeModel->getFirst('亚洲星风采');
        $json->xmljson(200,'请求成功',$startdata,'json');
    }
    //童艺光年
    public function getlight(){
        $json=new \Lib\api\ResponseJson;
        $typeModel=D("Admin/Movie");
        $startdata=$typeModel->getFirst('童艺光年');
        $json->xmljson(200,'请求成功',$startdata,'json');
    }
        //舞力派
    public function getdance(){
        $json=new \Lib\api\ResponseJson;
        $typeModel=D("Admin/Movie");
        $startdata=$typeModel->getFirst('舞力派');
        $json->xmljson(200,'请求成功',$startdata,'json');
    }
    //星光校园
    public function getschool(){
        $json=new \Lib\api\ResponseJson;
        $typeModel=D("Admin/Movie");
        $startdata=$typeModel->getFirst('星光校园');
        $json->xmljson(200,'请求成功',$startdata,'json');
    }
    //星艺华彩
    public function gethuacai(){
        $json=new \Lib\api\ResponseJson;
        $typeModel=D("Admin/Movie");
        $startdata=$typeModel->getFirst('星艺华采');
        $json->xmljson(200,'请求成功',$startdata,'json');
    }
    //亚洲校园星
    public function getschoolstar(){
        $json=new \Lib\api\ResponseJson;
        $typeModel=D("Admin/Movie");
        $startdata=$typeModel->getFirst('亚洲校园星');
        $json->xmljson(200,'请求成功',$startdata,'json');
    }
    public function getperform(){
        $json=new \Lib\api\ResponseJson;
        $typeModel=D("Admin/Movie");
        $startdata=$typeModel->getFirst('社区演出');
        $json->xmljson(200,'请求成功',$startdata,'json');
    }
    
    public function getpersonal(){
        $json=new \Lib\api\ResponseJson;
        if(IS_POST){
            $model=D('Admin/Member');
            $scdata=$model->getInformation();
            if($scdata!=false){
                $json->xmljson('200',"请求成功",$scdata,'json');
            }
            if($model->getError()&&is_array($model->getError())){
                foreach ($model->getError() as $k => $v){
                        $json->xmljson($k,$v,'','json');
                }
            }
            $json->xmljson(400,$model->getError(),'','json');
        }
    }
    public function addcolec(){
        $json=new \Lib\api\ResponseJson;
        $model=D("Admin/Collection");
        if($model->setColect()){
            $json->xmljson(200,'收藏成功','','json');
        }
        if($model->getError()&&is_array($model->getError())){
            foreach ($model->getError() as $k => $v){
                $json->xmljson($k,$v,'','json');
            }
        }
        $json->xmljson(400,$model->getError(),'','json');
    }
    public function favourite(){
        $json=new \Lib\api\ResponseJson;
        $favModel=D("Admin/Favourite");
        $favdata=$favModel->setFavourite();
        if($favdata){
            $json->xmljson(200,'点赞成功',$favdata,'json');
        }
        if($favModel->getError()&&is_array($favModel->getError())){
            foreach ($favModel->getError() as $k => $v){
                $json->xmljson($k,$v,'','json');
            }
        }
        $json->xmljson(400,$favModel->getError(),'','json');
            
    }
    public function getsearch(){
        $json=new \Lib\api\ResponseJson;
        $movModel=D("Admin/Movie");
        $data=$movModel->appsearch();
        if($data!= false){
            $json->xmljson(200,'success',$data,'json');
        }
        if($movModel->getError()&&is_array($movModel->getError())){
            foreach ($movModel->getError() as $k => $v){
                $json->xmljson($k,$v,'','json');
            }
        }
        $json->xmljson(400,$favModel->getError(),'','json');
    }
    public function hotsearch(){
        $json=new \Lib\api\ResponseJson;
        $hsModel=D("Admin/Hotsearch");
        $hsdata=$hsModel->getHotsearch();
        $json->xmljson(200,'success',$hsdata,'json');  
    }
    public function logout(){
        $json=new \Lib\api\ResponseJson;
        $model=D("Admin/Member");
        if($model->logout()){
                $json->xmljson(200,'success','','json');
        }
        if($model->getError()&&is_array($model->getError())){
            foreach ($model->getError() as $k => $v){
                $json->xmljson($k,$v,'','json');
            }
        }
        $json->xmljson(400,$model->getError(),'','json');
    }
     public function cancle(){
        $json=new \Lib\api\ResponseJson;
        $model=D("Admin/Collection");
        if($model->cancle()){
                $json->xmljson(200,'success','','json');
        }
        if($model->getError()&&is_array($model->getError())){
            foreach ($model->getError() as $k => $v){
                $json->xmljson($k,$v,'','json');
            }
        }
        $json->xmljson(400,$model->getError(),'','json');
    }
    //忘记密码
    public function editpassword(){
        $json=new \Lib\api\ResponseJson;
        $model=D("Admin/Member");
        $res=$model->editPassword();
        if($res){
            $json->xmljson(200,'修改密码成功','','json');
        }
        if($model->getError()&&is_array($model->getError())){
            foreach ($model->getError() as $k => $v){
                $json->xmljson($k,$v,'','json');
            }
        }
        $json->xmljson(400,$model->getError(),'','json');
    }
    
    
    
}




















