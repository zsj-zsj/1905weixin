<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Usermodel extends Model
{
    public $primaryKey='uid';
    protected $table='p_users';
}
