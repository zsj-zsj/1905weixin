<?php
namespace App\Http\Controllers\Weixin;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Model\WxUserModel;  //关注的用户信息
use App\Model\WxImgModel;   //图片
use App\Model\WxMsgModel;   //留言
use App\Model\WxVoiceModel; //语音

use Illuminate\Support\Facades\Redis;
use GuzzleHttp\Client;

class Wxcontroller extends Controller
{
    protected $access_token;
    public function __construct(){
        //获取access_token
        $this->access_token=$this->getAccessToken();
    }

    public function test(){
        echo $this->access_token;
    }
    protected function getAccessToken(){
        $key='wx_access_token';
        $access_token=Redis::get($key);
        if($access_token){
            return $access_token;
        }
        $url='https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid='.env('WX_APPID').'&secret='.env('WX_APPSECRET').'';
        $data_json=file_get_contents($url);
        $arr=json_decode($data_json,true);

        Redis::set($key,$arr['access_token']);
        Redis::expire($key,3600);

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
        //判断消息类型

        
        $touser=$xml_obj->FromUserName;     //接受消息用户openid
        $fromuser=$xml_obj->ToUserName;     //开发者公众号id
        $time=time();
        $event=$xml_obj->Event;   //获取事件类型
        
        if($event=='subscribe'){
            $openid=$xml_obj->FromUserName;   //获取用户openid
            
            $u=WxUserModel::where(['openid'=>$openid])->first();
            if($u){
                //第二次关注
                
                $name='欢迎回来';
                $guanzhu='<xml>
                <ToUserName><![CDATA['.$openid.']]></ToUserName>
                <FromUserName><![CDATA['.$fromuser.']]></FromUserName>
                <CreateTime>'.$time.'</CreateTime>
                <MsgType><![CDATA[text]]></MsgType>
                <Content><![CDATA['.$name.']]></Content>
              </xml>';
                echo $guanzhu;
            }else{
                //第一次关注

                $wxuser='https://api.weixin.qq.com/cgi-bin/user/info?access_token='.$this->access_token.'&openid='.$openid.'&lang=zh_CN';
                $wx_user=file_get_contents($wxuser);
                $WXUser=json_decode($wx_user,true);

                $user_data=[
                    'sex'=>$WXUser['sex'],
                    'openid'=>$openid,
                    'sub_time'=>$xml_obj->CreateTime,
                    'nickname'=>$WXUser['nickname'],
                    'headimgurl'=>$WXUser['headimgurl']
                ];
                
                $uid=WxUserModel::insertGetId($user_data);

                $name='感谢您的关注~@'.$WXUser['nickname'];
                $guanzhu='<xml>
                <ToUserName><![CDATA['.$openid.']]></ToUserName>
                <FromUserName><![CDATA['.$fromuser.']]></FromUserName>
                <CreateTime>'.$time.'</CreateTime>
                <MsgType><![CDATA[text]]></MsgType>
                <Content><![CDATA['.$name.']]></Content>
                </xml>';
                echo $guanzhu;
            }
            $url='https://api.weixin.qq.com/cgi-bin/user/info?access_token='.$this->access_token.'&openid='.$openid.'&lang=zh_CN';
            $user_info=file_get_contents($url);
            file_put_contents('wx_user.log',$user_info,FILE_APPEND);  
        }elseif($event=='CLICK'){
            //天气
            $weather_api="https://free-api.heweather.net/s6/weather/now?location=beijing&key=b7866e916696476b8e04239d77e6a008";
            $weather_info=file_get_contents($weather_api);
            $arr=json_decode($weather_info,true);
           
            $cond_txt=$arr['HeWeather6'][0]['now']['cond_txt'];
            $tmp=$arr['HeWeather6'][0]['now']['tmp'];
            $wind_dir=$arr['HeWeather6'][0]['now']['wind_dir'];

            $msg='天气：'.$cond_txt."\n" .'温度：'.$tmp. "\n" .'风向：'.$wind_dir;
            $timea='当前时间'.date('Y-m-d H:i:s')."\n".$msg;
            
            if($xml_obj->EventKey=='keykey'){
                $tianqi = '<xml>
                <ToUserName><![CDATA['.$touser.']]></ToUserName>
                <FromUserName><![CDATA['.$fromuser.']]></FromUserName>
                <CreateTime>'.$time.'</CreateTime>
                <MsgType><![CDATA[text]]></MsgType>
                <Content><![CDATA['.$timea.']]></Content>
                </xml>';
                echo $tianqi;
            }
        }
        
        $media_id=$xml_obj->MediaId;
        $msg_type=$xml_obj->MsgType;

        $idd=WxUserModel::where('openid','=',$touser)->value('uid');
        //文字
        if($msg_type=='text'){

            $content = date('Y-m-d H:i:s') . $xml_obj->Content;
            $msgss=$xml_obj->Content;
            $data=[
                'time'=>$time,
                'msg'=>$msgss,
                'uid'=>$idd
            ];
            $response_text=WxMsgModel::insert($data);

            $response_text = '<xml>
                            <ToUserName><![CDATA['.$touser.']]></ToUserName>
                            <FromUserName><![CDATA['.$fromuser.']]></FromUserName>
                            <CreateTime>'.$time.'</CreateTime>
                            <MsgType><![CDATA[text]]></MsgType>
                            <Content><![CDATA['.$content.']]></Content>
                            </xml>';
            echo $response_text;            // 回复用户消息
        }elseif($msg_type=='image'){
        //图片
            $img=$this->getMedia($media_id,$msg_type);
            
            $data=[
                'time'=>$time,
                'img'=>$img,
                'uid'=>$idd
            ];
            $images=WxImgModel::insert($data);

            $images='<xml>
                <ToUserName><![CDATA['.$touser.']]></ToUserName>
                <FromUserName><![CDATA['.$fromuser.']]></FromUserName>
                <CreateTime>'.$time.'</CreateTime>
                <MsgType><![CDATA[image]]></MsgType>
                <Image>
                <MediaId><![CDATA['.$media_id.']]></MediaId>
                </Image>
            </xml>';
                echo $images;
        }elseif($msg_type=='voice'){
        //语音
            $voice=$this->getMedia($media_id,$msg_type);

            $data=[
                'uid'=>$idd,
                'voice'=>$voice,
                'time'=>$time
            ];
            $yuyin=WxVoiceModel::insert($data);

            $yuyin='<xml>
            <ToUserName><![CDATA['.$touser.']]></ToUserName>
            <FromUserName><![CDATA['.$fromuser.']]></FromUserName>
            <CreateTime>'.$time.'</CreateTime>
            <MsgType><![CDATA[voice]]></MsgType>
            <Voice>
            <MediaId><![CDATA['.$media_id.']]></MediaId>
            </Voice>
            </xml>';
            echo $yuyin;
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

    //语音图片类型地址
    public function getMedia($media_id,$msg_type){
        $url='https://api.weixin.qq.com/cgi-bin/media/get?access_token='.$this->access_token.'&media_id='.$media_id;
        //获取素材内容
        $client=new Client();
        $response=$client->request('GET',$url);
        //获取文件类型
        $content_type=$response->getHeader('Content-Type')[0];
        //dd($content_type);
        $pos=strpos($content_type,'/');
        // dd($pos);
        $extension='.' . substr($content_type,$pos+1);
        // dd($extension);
        //获取文件内容
        $file_content=$response->getBody();
        //保存文件
        $save_path='weixin_medis/';
        if($msg_type=='image'){
            $file_name=date('YmdHis').mt_rand(111,999).$extension;
            $save_path=$save_path . 'image/' .$file_name;
        }elseif($msg_type=='voice'){
            $file_name=date('YmdHis').mt_rand(111,999).$extension;
            $save_path=$save_path . 'voice/' .$file_name;
        }
        file_put_contents($save_path,$file_content);
        return $save_path;
    }

    //菜单
    public function caidan(){
        $url="https://api.weixin.qq.com/cgi-bin/menu/create?access_token=".$this->access_token;
        $urlEncode=urlencode('http://1905zhangshaojie.comcto.com/vote');
        $urlEncodeabc=urlencode('http://1905zhangshaojie.comcto.com/index/wxlogin');
        $erweima=urlencode('http://1905zhangshaojie.comcto.com/wx/rwm');
        
        $menu=[
            'button' => [
              [
                  'type'=>'view',
                  'name'=>'商城',
                  "url"=>'https://open.weixin.qq.com/connect/oauth2/authorize?appid=wx8bc80f5949fda528&redirect_uri='.$urlEncodeabc.'&response_type=code&scope=snsapi_userinfo&state=STATE#wechat_redirect'
              ],
            
              [
                "name"=>"菜单",
                "sub_button"=>[
                    [
                        "type"=>"view",
                        "name"=>"投票",
                        "url"=>'https://open.weixin.qq.com/connect/oauth2/authorize?appid=wx8bc80f5949fda528&redirect_uri='.$urlEncode.'&response_type=code&scope=snsapi_userinfo&state=STATE#wechat_redirect'
                    ],
                    [
                        "type"=>"click",
                        "name"=>"获取天气",
                        "key"=>"keykey",
                    ],
                    [
                        'type'=>'view',
                        'name'=>'点我♥',
                        'url'=>'https://open.weixin.qq.com/connect/oauth2/authorize?appid=wx8bc80f5949fda528&redirect_uri='.$erweima.'&response_type=code&scope=snsapi_userinfo&state=STAT#wechat_redirect'
                    ]
                ]
              ]
            ]
        ];
        
        $menu_json=json_encode($menu,JSON_UNESCAPED_UNICODE);
        $client= new Client();
        $response=$client->request('POST',$url,[
            'body'=>$menu_json
        ]);
        echo $response->getBody();
    }

    //群发消息
    public function sendmsg(){
        $openid=WxUserModel::select('openid','nickname','sex')->get()->toArray();
        $open=array_column($openid,'openid');
        $url="https://api.weixin.qq.com/cgi-bin/message/mass/send?access_token=".$this->access_token;
        
            $weather_api="https://free-api.heweather.net/s6/weather/now?location=beijing&key=b7866e916696476b8e04239d77e6a008";
            $weather_info=file_get_contents($weather_api);
            $arr=json_decode($weather_info,true);
           
            $cond_txt=$arr['HeWeather6'][0]['now']['cond_txt'];
            $tmp=$arr['HeWeather6'][0]['now']['tmp'];
            $wind_dir=$arr['HeWeather6'][0]['now']['wind_dir'];

            $msg='天气：'.$cond_txt."\n" .'温度：'.$tmp. "\n" .'风向：'.$wind_dir;
            $timea='当前时间'.date('Y-m-d H:i:s')."\n".$msg."\n".'大吉大利 晚上吃鸡 over over';

        $data=[
            'touser'=>$open,
            'msgtype'=>'text',
            'text'=>["content"=>$timea]
        ];
    
        $json=json_encode($data,JSON_UNESCAPED_UNICODE);
        
        $client= new Client();
        $response=$client->request('POST',$url,[
            'body' =>$json
        ]);
        
        echo $response->getBody();
    }

    //生成二维码
    public function rwm(){
        $url="https://api.weixin.qq.com/cgi-bin/qrcode/create?access_token=".$this->access_token;
        $data=[
            'expire_seconds'=>604800,
            'action_name'=>'QR_SCENE',
            'action_info'=>[
                'scene'=>[
                    'scene_id'=>'1012'
                ]
            ]
        ];

        $json=json_encode($data,JSON_UNESCAPED_UNICODE);
        $client= new Client();
        $response=$client->request('POST',$url,[
            'body'=> $json
        ]);

        echo $ticket=$response->getBody();

        $sc_ticket=json_decode($ticket,true);
       // dump($sc_ticket);
        $url_ticket=urlencode($sc_ticket['ticket']);
        //dump($url_ticket);
        $ticket_url='https://mp.weixin.qq.com/cgi-bin/showqrcode?ticket='.$url_ticket;
        return redirect($ticket_url);

        // $urll=file_get_contents($ticket_url);
        // file_put_contents('rwm.jpg',$urll);

    }
}