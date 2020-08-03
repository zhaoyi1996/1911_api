<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Model\Token;
use App\Puser;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Redis;
class LoginController extends Controller
{
    /*
     * 登录
     * */
    public function login(Request $request)
    {
        $data=$request->post();
        $userInfo=Puser::where('user_name',$data['user_name'])->first();
        if(!empty($userInfo)){
            if(password_verify($data['user_pwd'],$userInfo->user_pwd)){
                $time=time();
                $key='user_'.$userInfo->user_id;
                //查看hash里面是否有数据
                $hash=Redis::hgetall($key);
                if(empty($hash)){
                    //将信息存入redis
                    $access_token=Str::random(32);
                    $hash=[
                        'key_id'   =>     $key,
                        'access_token'  => $access_token,
                        'add_time'  => $time,
                    ];
                    Redis::HMSET($key,$hash);
                }
                //判断token是否过期
                if($hash['add_time']-$time>7200){
                    //token过期了
                    //将信息存入redis
                    $access_token=Str::random(32);
                    $hash=[
                        'key_id'   =>     $key,
                        'access_token'  => $access_token,
                        'add_time'  => $time,
                    ];
                    Redis::HMSET($key,$hash);
                }
                //将hash里面的时间戳弹出
                array_pop($hash);
                $data=[
                    'errno'      => 0,
                    'msg'      => 'ok',
                    'data'     =>$hash,
                ];
            }else{
                $data=[
                    "errno"     =>200002,
                    "msg"       =>"no",
                    "data"      =>[
                        'error' => '登录失败，用户名或密码错误',
                    ]
                ];
            }
        }else{
            $data=[
                "errno"     =>200001,
                "msg"       =>"no",
                "data"      =>[
                    'error' => '登录失败,用户名或密码错误',
                ]
            ];
        }
        echo json_encode($data,JSON_UNESCAPED_UNICODE);

    }
}
