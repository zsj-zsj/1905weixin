<?php

namespace App\Http\Controllers\Index;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Model\WxUserModel; 
use App\Model\WxGoodsModel;
use Illuminate\Support\Str;

class IndexController extends Controller
{
    public function  wxlogin(){
        $code=$_GET['code'];
        // dump($code);
        $data=$this->accesstoken($code);
        // dump($data);
        // $url = "https://api.weixin.qq.com/sns/oauth2/access_token?appid=".env('WX_APPID')."&secret=".env('WX_APPSECRET')."&code={$code}&grant_type=authorization_code";
        // $res_json = file_get_contents($url);    // 请求接口，获取json响应
        // $data = json_decode($res_json,true);
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

        //返回到这个视图
        return  redirect('/');
    }


    public function index(){
        $goodsindex=WxGoodsModel::paginate(4);
        $fenye=request()->all();

        //微信配置
        $nonceStr = Str::random(8);
        $signature = "";
        $wx_config = [
            'appId'     => env('WX_APPID'),
            'timestamp' => time(),
            'nonceStr'  => $nonceStr,
            'signature' => $signature,
            'jsApiList' => ['updateAppMessageShareData']
        ];
        $ticket = WxUserModel::getJsapiTicket();
        $url = $_SERVER['APP_URL'] . $_SERVER['REQUEST_URI'];;      //  当前url
        $jsapi_signature = WxUserModel::jsapiSign($ticket,$url,$wx_config);
        $wx_config['signature'] = $jsapi_signature;

        return view('Index.index',['goodsindex'=>$goodsindex,'fenye'=>$fenye,'wx_config'=>$wx_config]);
    }

    //根据code获取accesstoken
    public function accesstoken($code){
        $url = "https://api.weixin.qq.com/sns/oauth2/access_token?appid=".env('WX_APPID')."&secret=".env('WX_APPSECRET')."&code={$code}&grant_type=authorization_code";
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
