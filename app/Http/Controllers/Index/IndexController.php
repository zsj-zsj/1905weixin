<?php

namespace App\Http\Controllers\Index;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Model\WxUserModel; 

class IndexController extends Controller
{
    public function index(){
        $code=$_GET['code'];
        $data=$this->accesstoken($code);

        $openid=$data['openid'];
        
        $tu=WxUserModel::where(['openid'=>$openid])->first();
        if($tu){
            //用户存在
            $userinfo=$tu->toArray();
        }else{
            //入库
            $userinfo=$this->userinfo($data['access_token'],$data['openid']);
            WxUserModel::insert();
        }
        session(['headimgurl'=>$tu['headimgurl']]);
        session(['nickname'=>$tu['nickname']]);
        return view('Index.index');
    }

    //根据code获取accesstoken
    public function accesstoken($code){
        $url='https://api.weixin.qq.com/sns/oauth2/access_token?appid=wx8bc80f5949fda528&secret=f4852897a0b441624d7c845c878f2548&code='.$code.'&grant_type=authorization_code';
        $data=file_get_contents($url);
        $json=json_decode($data,true);
        return $json;
    }

    //获取用户信息
    public function userinfo($access_token,$openid){
        $url='https://api.weixin.qq.com/sns/userinfo?access_token='.$access_token.'&openid='.$openid.'&lang=zh_CN';
        $info=file_get_contents($url);
        $json=json_decode($info,true);
        return $json;
    }
}
