<?php

namespace App\Http\Controllers\Ks;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class Wx extends Controller
{
    private function checkSignature()
{
    $signature = $_GET["signature"];
    $timestamp = $_GET["timestamp"];
    $nonce = $_GET["nonce"];
	$echostr=$_GET['echostr'];
    $token = 'zsj1234zsj1234zsj432';
    $tmpArr = array($token, $timestamp, $nonce);
    sort($tmpArr, SORT_STRING);
    $tmpStr = implode( $tmpArr );
    $tmpStr = sha1( $tmpStr );   
    if( $tmpStr == $signature ){
        return $echostr;
    }else{
        die;
    }
}
}
