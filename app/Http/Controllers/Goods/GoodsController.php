<?php

namespace App\Http\Controllers\Goods;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Model\WxGoodsModel;

class GoodsController extends Controller
{


    //商品详情页
    public function index($id){
        //微信配置
        $nonceStr = Str::random(8);
        $signature = "";
        $wx_config = [
            'appId'     => env('WX_APPID'),
            'timestamp' => time(),
            'nonceStr'  => $nonceStr,
            //'signature' => $signature,
            'jsApiList' => ['updateAppMessageShareData']
        ];
        $ticket = WxUserModel::getJsapiTicket();
        $url = $_SERVER['APP_URL'] . $_SERVER['REQUEST_URI'];;      //  当前url
        $jsapi_signature = WxUserModel::jsapiSign($ticket,$url,$wx_config);
        $wx_config['signature'] = $jsapi_signature;

        $index=WxGoodsModel::where('id','=',$id)->first();
        return view('goods.detail',['index'=>$index,'wx_config'=>$wx_config]);
    }
}
