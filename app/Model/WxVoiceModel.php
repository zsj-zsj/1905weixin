<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class WxVoiceModel extends Model
{
    public $primaryKey='voice_id';
    protected $table='wx_voice';
}
