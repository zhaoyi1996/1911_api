<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class LoginController extends Controller
{
    /*
     * 登录
     * */
    public function login(Request $request)
    {
        $data=$request->post();
        dd($data);
        $pwd=$request->post('user_pwd');
        $userInfo=Puser::where('user_name',$name)->first();
        if($userInfo){
            if(password_verify($pwd,$userInfo->user_pwd)){
                $access_token=Str::random(32);
                $time=7200;    //存储时间
                $token=new Token();
                $token->access_token=$access_token;
                $token->uid=$userInfo->user_id;
                $token->ex_time=time()+$time;
                $res=$token->save();
                if($res){
                    $data=[
                        'errno'      => '0',
                        'msg'      => 'ok',
                        'data'     =>[
                            'access_token' => $access_token,
                        ]
                    ];
                    return json_encode($data);
                }

            }else{
                $data=[
                    "errno"     =>200002,
                    "msg"       =>"no",
                    "data"      =>[
                        'error' => '登录失败',
                    ]
                ];
                echo json_encode($data,JSON_UNESCAPED_UNICODE);
            }
        }else{
            $data=[
                "errno"     =>200001,
                "msg"       =>"no",
                "data"      =>[
                    'error' => '登录失败',
                ]
            ];
            echo json_encode($data,JSON_UNESCAPED_UNICODE);
        }

    }
}
