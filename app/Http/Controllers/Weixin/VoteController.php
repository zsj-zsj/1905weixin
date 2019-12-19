<?php

namespace App\Http\Controllers\Weixin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redis;

class VoteController extends Controller
{
    public function delkey(){
        $key=$_GET['k'];
        echo 'Delete Key: '.$key;echo '</br>';
        Redis::del($key);
    }

    public function index(){
        $data=$_GET;
        if(empty($data)){
            return "想干嘛？在微信访问";
        }
        $code=$data['code'];

        //获取access_token
        $token=$this->code($code);
        if(empty($token['access_token'])){
            return "谁叫你刷新的？请重新进入";
        }
        //获取用户信息
        $access_token=$token['access_token'];
        $openid=$token['openid'];
        $xinxi=$this->user($access_token,$openid);
        

        //保存用户信息
        $xinxi_key='h:u:'.$token['openid'];
        Redis::hMset($xinxi_key,$xinxi);

        //存
        $key='ss:vote:zsj';
        //判断是否投票
        if(Redis::zrank($key,$token['openid'])){
            echo "已投票";echo '<br>';
        }else{
            Redis::Zadd($key,time(),$openid);
        }
        $total=Redis::zCard($key);
        echo '投票总人数：'.$total;echo '<br>';
        $number=Redis::zRange($key,0,-1,true);   //投票人openid
        foreach($number as $k=>$v){
            $uk='h:u:'.$k;
            $u=Redis::hgetAll($uk);
            echo "用户：".$k.'；投票时间：'.date('Y-m-d H:i:s',$v).'；用户头像'.'<img src="'.$u['headimgurl'].'">';echo '<br>';
        }   
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
