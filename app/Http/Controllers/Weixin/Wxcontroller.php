<?php

namespace App\Http\Controllers\Weixin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class Wxcontroller extends Controller
{
    protected $access_token;

    public function __construct(){
        //获取access_token
        $this->access_token=$this->getAccessToken();

    }

    protected function getAccessToken(){
        $url='https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid='.env('WX_APPID').'&secret='.env('WX_APPSECRET').'';
        $data_json=file_get_contents($url);
        $arr=json_decode($data_json,true);
        return $arr['access_token'];
    }


    //处理连接
    public function checkSignature(){
        $token= 'zsj1234zsj1234zsj432';
        $signature = $_GET["signature"];
        $timestamp = $_GET["timestamp"];
        $nonce = $_GET["nonce"];
        $echostr = $_GET['echostr'];
        $tmpArr = array($token, $timestamp, $nonce);
        sort($tmpArr, SORT_STRING);
        $tmpStr = implode( $tmpArr );
        $tmpStr = sha1( $tmpStr );
        if( $tmpStr == $signature ){
                echo $echostr;
        }else{
                die("not ok");
        }
    }

    //接受微信推送事件
    public function receiv(){
        //将接受的数据记录到日志文件
        $log_file="wx.log";
        $xml_str = file_get_contents("php://input");
        $data = date('Y-m-d H:i:s') . $xml_str;
        file_put_contents($log_file,$data,FILE_APPEND);

        $xml_obj = simplexml_load_string($xml_str);  //处理xml数据

        $event=$xml_obj->Event;   //获取事件类型
        if($event=='subscribe'){
            $openid=$xml_obj->FromUserName;   //获取用户openid
            $url='https://api.weixin.qq.com/cgi-bin/user/info?access_token='.$this->access_token.'&openid='.$openid.'&lang=zh_CN';
            $user_info=file_get_contents($url);
            file_put_contents('wx_user.log',$user_info,FILE_APPEND);  
        }

        //判断消息类型
        $msg_type=$xml_obj->MsgType;
        $touser=$xml_obj->FromUserName;     //接受消息用户openid
        $fromuser=$xml_obj->ToUserName;     //开发者公众号id
        $time=time();

        if($msg_type=='text'){
            $content = date('Y-m-d H:i:s') . $xml_obj->Content;
            $response_text = '<xml>
                            <ToUserName><![CDATA['.$touser.']]></ToUserName>
                            <FromUserName><![CDATA['.$fromuser.']]></FromUserName>
                            <CreateTime>'.$time.'</CreateTime>
                            <MsgType><![CDATA[text]]></MsgType>
                            <Content><![CDATA['.$content.']]></Content>
                            </xml>';
            echo $response_text;            // 回复用户消息
        }

        

        
    }



    //获取用户基本信息
    public function  getUserInfo($access_token,$openid){
        $url ="https://api.weixin.qq.com/cgi-bin/user/info?access_token='.$access_token.'&openid='.$openid.'&lang=zh_CN";
        //发送网络请求
        $json_str=file_get_contents($url);
        $log_file='wx_user.log';
        file_put_contents($log_file,$json_str,FILE_APPEND);
    }
}
