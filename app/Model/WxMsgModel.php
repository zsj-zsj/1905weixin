<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class WxMsgModel extends Model
{
    public $primaryKey='msg_id';
    protected $table='wx_msg';
}
