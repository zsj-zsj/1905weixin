<?php

namespace App\Http\Controllers\Weixin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class VoteController extends Controller
{
    public function index(){
        $data=$_GET;
        $code=$data['code'];

        //获取access_token
        $token=$this->code($code);
        //获取用户信息
        $openid=$token['openid'];
        $access_token=$token['access_token'];
        $xinxi=$this->user($openid,$access_token);
        dd($xinxi);
    }
    
    //获取code
    public function code($code){
        $url='https://api.weixin.qq.com/sns/oauth2/access_token?appid=wx8bc80f5949fda528&secret=f4852897a0b441624d7c845c878f2548&code='.$code.'&grant_type=authorization_code';
        $dataa=file_get_contents($url);
        $json=json_decode($dataa);
        return $json;

    }

    //获取用户信息
    public function user($openid,$access_token){
        $url='https://api.weixin.qq.com/sns/userinfo?access_token='.$access_token.'&openid='.$openid.'&lang=zh_CN';
        $info=file_get_contents($url);
        $json=json_decode($info);
        return $json;
    }
}
