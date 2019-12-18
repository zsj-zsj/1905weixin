<?php

namespace App\Http\Controllers\Weixin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redis;

class VoteController extends Controller
{
    public function index(){
        $data=$_GET;
        if(empty($data)){
            return "想干嘛?";
        }
        $code=$data['code'];

        //获取access_token
        $token=$this->code($code);
        //获取用户信息
        $access_token=$token['access_token'];
        $openid=$token['openid'];
        $xinxi=$this->user($access_token,$openid);
        
        $redis_key='vote';
        $number=Redis::incr($redis_key);
        echo "投票成功，当前票数：5869656".$number;

    }
    
    //获取code
    public function code($code){
        $url='https://api.weixin.qq.com/sns/oauth2/access_token?appid=wx8bc80f5949fda528&secret=f4852897a0b441624d7c845c878f2548&code='.$code.'&grant_type=authorization_code';
        $dataa=file_get_contents($url);
        $json=json_decode($dataa,true);
        return $json;

    }

    //获取用户信息
    public function user($access_token,$openid){
        $url='https://api.weixin.qq.com/sns/userinfo?access_token='.$access_token.'&openid='.$openid.'&lang=zh_CN';
        $info=file_get_contents($url);
        $json=json_decode($info,true);
        return $json;
    }
}
