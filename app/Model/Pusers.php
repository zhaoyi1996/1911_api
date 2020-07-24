<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Pusers extends Model
{
    //指定表名
    protected $table = 'p_users';
    //指定主键  id
    protected $primaryKey = 'user_id';
    //时间戳
    public $timestamps = false;
}
