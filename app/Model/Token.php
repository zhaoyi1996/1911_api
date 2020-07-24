<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Token extends Model
{
    //指定表名
    protected $table = 'token';
    //指定主键  id
    protected $primaryKey = 'id';
    //时间戳
    public $timestamps = false;
}
