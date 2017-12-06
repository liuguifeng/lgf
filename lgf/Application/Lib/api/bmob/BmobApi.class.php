<?php
namespace Lib\api\bmob;
use Lib\api\bmob\lib\BmobSms;
use Lib\api\bmob\lib\BmobObject;
class BmobApi{
    public function bmobcheck($mobie){//验证注册的方法
        $bmobSms = new \Lib\api\bmob\lib\BmobSms;
        if(!is_numeric($regid)||!is_numeric($regid)){
            return false;
        }
        $res = $bmobSms->sendSmsVerifyCode($mobie);
        return $res;
        $res = $bmobSms->querySms($smsId);
        if($res['sms_state']=='FAIL'||$res['verify_state']==true){
            return false;
        }
        $res = $bmobSms->verifySmsCode($mobie,$regid);
        if($res['msg']=='ok'){
            return true;
        }
    }

}
