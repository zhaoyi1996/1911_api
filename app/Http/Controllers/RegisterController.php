<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Puser;
use Illuminate\Support\Str;

class RegisterController extends Controller
{
    /*
     * 注册
     * */
    public function reg(){
        $data=request()->post();
        //唯一性验证
        $user_info=Puser::where('user_name',$data['user_name'])->first();
        if(!empty($user_info)){
            $data=[
                'errno' => 50001,
                'msg'   => '用户已存在',
            ];
        }else{
            $user= new Puser();
            $user->user_name=$data['user_name'];
            $user->user_pwd=password_hash($data['user_pwd'],PASSWORD_BCRYPT);
            $user->user_time=time();
            $user->user_email=$data['user_email'];
            $res=$user->save();
            if($user){
                $data=[
                    'errno' => 0,
                    'msg'   =>'注册成功'
                ];
            }else{
                $data=[
                    'errno' =>50002,
                    'msg'   =>'注册失败'
                ];
            }
        }
        echo json_encode($data,JSON_UNESCAPED_UNICODE);
    }
}
