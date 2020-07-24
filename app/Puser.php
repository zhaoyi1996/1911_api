<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Puser extends Model
{
    //指定表名
    protected $table = 'p_user';
    //指定主键  id
    protected $primaryKey = 'user_id';
    //时间戳
    public $timestamps = false;
    protected $guarded = [];
}
