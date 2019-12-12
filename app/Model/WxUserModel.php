<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class WxUserModel extends Model
{
    public $primaryKey='uid';
    protected $table='wx_user';
}
