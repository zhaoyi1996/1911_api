<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redis;

class HashController extends Controller
{
    //redis中哈希练习
    public function hash1(){
        $data=[
            'name'  =>'赵四',
            'email' =>'1113252039@qq.com',
            'age'   =>'99',
            'sex'   =>'中性'
        ];
        $key='user_info1';
        Redis::hMset($key,$data);
    }
    public function hash2(){
        $key='user_info1';
        $data=Redis::hgetall($key);
        dd($data);

    }
}
