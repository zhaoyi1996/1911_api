<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Pgoods extends Model
{
    //指定表名
    protected $table = 'p_goods';
    //指定主键  id
    protected $primaryKey = 'goods_id';
    //时间戳
    public $timestamps = false;
}
