<?php
namespace api\app;
class ResponseJson{
    const DEFAULTJSON='json';
    public function xmljson($code,$message="",$data=array(),$type=self::DEFAULTJSON){
        if(!is_numeric($code)){
            return "";
        }
        $type=isset($_GET['format'])?strtolower($_GET['format']):self::DEFAULTJSON;
        $result=array(
            'code'=>$code,
            'message'=>$message,
            'data'=>$data
        );
        if($type=='json'){
            self::json($code,$message,$data);
            exit;
        }elseif($type=='array'){
            var_dump($result);
        }elseif(($type=='xml')){
            self::xml($code,$message,$data);
            exit;
        }else{
            //
        }
    }
    /*
    按json方式输出通信数据
     * @param integer $code 状态码
     * $param string $message 提示信息
     * $param array $data 数据 
     *      */
    public static function json($code,$message="",$data=array()){
        if(!is_numeric($code)){
            return "";
        }
        $result=array(
            'code'=>$code,
            'message'=>$message,
            'data'=>$data
        );
        echo json_encode($result);
        exit;
    }
    public static function xml($code,$message,$data=array()){
        if(!is_numeric($code)){
            return "";
        }
        $result=array(
            'code'=>$code,
            'message'=>$message,
            'data'=>$data
        );
        header('Content-Type:text/xml');
        $xml="<?xml version='1.0' encoding='utf-8'?>\n";
        $xml.="<root>\n";
        $xml.=self::xmlToencode($result);
        $xml.="</root>\n";
        echo $xml;
        
    }
    public static function xmlToencode($data){
        $xml=$attr='';
        foreach($data as $key => $value){
            if(is_numeric($key)){
                $attr=" id='{$key}'";//转换成id属性，记得id前面有个空格
                $key="item";
            }
            $xml.="<{$key}{$attr}>";
            $xml.=is_array($value)?self::xmlToencode($value):$value;
            $xml.="</{$key}>";
        }
        return $xml;
    }
}

