<?php

namespace App\Http\Controllers\Lianxi;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

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
        


    }


}
