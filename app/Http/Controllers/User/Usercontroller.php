<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Model\Usermodel;
use Illuminate\Support\Facades\Redis;

use GuzzleHttp\Client;

class Usercontroller extends Controller
{
    public function create(){
        $pass='443443';
        $password=password_hash($pass,PASSWORD_BCRYPT);
        $data=[
            'user_name'=>'李四',
            'password'=>$password,
            'email'=>'09@qq.com',
        ];

        $res=Usermodel::insertGetId($data);
        dd($res); 
    }

    public function rediss(){
        $key='weixin';
        $val='hello';
        Redis::set($key,$val);

        echo time(); echo '</br>';
        echo date('Y-m-d H:i:s');
    }

    public function baidu(){
        $url ='http://news.baidu.com/';
        $client = new Client();
        $response = $client->request('GET',$url);
        echo $response->getBody();
    }
}
