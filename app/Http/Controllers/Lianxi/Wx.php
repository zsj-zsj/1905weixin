<?php

namespace App\Http\Controllers\Lianxi;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Model\WxUserModel;
class Wx extends Controller
{
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


    public function clsj(){
        $rizhi='lianyi.log';
       
        $file=file_get_contents("php://input");
        $date=date('Y-m-d H:i:s').$file;
        file_put_contents($rizhi,$date,FILE_APPEND);
        $xml=simplexml_load_string($file);

        $openid=$xml->FromUserName;
        
        $access=WxUserModel::getAccessToken();

        $url='https://api.weixin.qq.com/cgi-bin/user/info?access_token='.$access.'&openid='.$openid.'&lang=zh_CN';
        $shuzu=file_get_contents($url);
        $sj=json_decode($shuzu,true);

       $data=[
           'nickname'=>$sj['nickname'],
            'openid'=>$sj['openid'],
            'sub_time'=>time(),
            'sex'=>$sj['sex'],
            'headimgurl'=>$sj['headimgurl']
       ];      
        $ToUserName=$xml->ToUserName;
        $Event=$xml->Event;

        
        if($Event=='subscribe'){
            $yonghu=WxUserModel::where('openid','=',$openid)->first();
            if($yonghu){
                $name="欢迎回来"; 
               $wenben='<xml>
               <ToUserName><![CDATA['.$openid.']></ToUserName>
               <FromUserName><![CDATA['.$ToUserName.']]></FromUserName>
               <CreateTime>'.time().'</CreateTime>
               <MsgType><![CDATA[text]]></MsgType>
               <Content><![CDATA['.$name.']]></Content>
             </xml>';
             echo $wenben;
            }else{
                $yonghu=WxUserModel::insert($data);
                $name='欢迎您的关注：'.$data['nickname'];
                $wenben='<xml>
                <ToUserName><![CDATA['.$openid.']></ToUserName>
                <FromUserName><![CDATA['.$ToUserName.']]></FromUserName>
                <CreateTime>'.time().'</CreateTime>
                <MsgType><![CDATA[text]]></MsgType>
                <Content><![CDATA['.$name.']]></Content>
              </xml>';
              echo $wenben;
            }   
        }

        $mediaid=$xml->MediaId;
        $msg_type=$xml->MsgType;
        if($msg_type=='text'){
            $Content=$xml->Content;
            $xiaoxi='<xml>
            <ToUserName><![CDATA['.$openid.']]></ToUserName>
            <FromUserName><![CDATA['.$ToUserName.']]></FromUserName>
            <CreateTime>'.time().'</CreateTime>
            <MsgType><![CDATA[text]]></MsgType>
            <Content><![CDATA['.$Content.']]></Content>
          </xml>';
          echo $xiaoxi;
        }elseif($msg_type=='image'){
            $tupian='<xml>
                <ToUserName><![CDATA['.$openid.']]></ToUserName>
                <FromUserName><![CDATA['.$ToUserName.']]></FromUserName>
                <CreateTime>12345678</CreateTime>
                <MsgType><![CDATA[image]]></MsgType>
                <Image>
                <MediaId><![CDATA['.$mediaid.']]></MediaId>
                </Image>
            </xml>';
            echo $tupian;
        }elseif($msg_type=='voice'){
            $yuyin='<xml>
            <ToUserName><![CDATA['.$openid.']]></ToUserName>
            <FromUserName><![CDATA['.$ToUserName.']]></FromUserName>
            <CreateTime>'.time().'</CreateTime>
            <MsgType><![CDATA[voice]]></MsgType>
            <Voice>
              <MediaId><![CDATA['.$mediaid.']]></MediaId>
            </Voice>
          </xml>';
          echo $yuyin;
        }
        

    }


}
