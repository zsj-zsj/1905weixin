<?php

namespace App\Http\Controllers\Goods;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Model\WxGoodsModel;

class GoodsController extends Controller
{
    //商品详情页
    public function index(){
        $goods_id=request()->input('id');
        $index=WxGoodsModel::first();
        return view('goods.detail',['index'=>$index]);
    }
}
