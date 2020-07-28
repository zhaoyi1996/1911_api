<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use GuzzleHttp\Client;
use Illuminate\Support\Str;
use App\Model\Pusers;
use App\Puser;
use App\Model\Token;

class TestController extends Controller
{
    //测试获取微信公众号access_token
    public function getToken(){
        $appid="wx4d62ce195d3535fb";
        $secret="feb4b5c996f0afd2eee7fc1c6127c5b8";
        $url="https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=".$appid."&secret=".$secret;
        $cont=file_get_contents($url);
        echo $cont;
    }
    //curl方式获取
    public function getCurltoken(){
        $appid="wx4d62ce195d3535fb";
        $secret="feb4b5c996f0afd2eee7fc1c6127c5b8";
        $url="https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=".$appid."&secret=".$secret;
        //curl初始化
        $curl = curl_init();
        //设置要爬取的网页的网址
        curl_setopt($curl, CURLOPT_URL, $url);
        //将 curl_exec()获取的信息以文件流的形式返回，而不是直接输出。设置为0是直接输出
        curl_setopt($curl,CURLOPT_RETURNTRANSFER,1);
        //如果你想把一个头包含在输出中，设置这个选项为一个非零值，我这里是不要输出，所以为 0
        curl_setopt($curl, CURLOPT_HEADER, 0);
        //执行curl,抓取内容
        $content = curl_exec($curl);
        //关闭会话
        curl_close($curl);
        dd($content);
    }
    //guzzle获取access_token
    public function getGuzzleToken(){
        $appid="wx4d62ce195d3535fb";
        $secret="feb4b5c996f0afd2eee7fc1c6127c5b8";
        $url="https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=".$appid."&secret=".$secret;
        $client= new Client();
        $response = $client->get($url);
        $body = $response->getBody();
        echo $body;
    }
    public function access_token(){
        $access_token=Str::random(32);
        $data=[
            'access_token'  =>$access_token,
            'expire_in'     =>7200
        ];
        return json_encode($data);
    }
    //调用www里面的用户信息
    public function getuserinfo(){
        $url="http://www.1911api.com/userinfo";
        $data=file_get_contents($url);
        dd($data);
    }
    //注册
    public function sign(Request $request){
        //唯一性验证
        $user_name=$request->post('user_name');
        $userinfo=Pusers::where('user_name',$user_name)->first();
        if(!empty($userinfo)){
            $data=[
                "errno"     =>60001,
                "msg"       =>"用户名已存在"
            ];
            echo json_encode($data,JSON_UNESCAPED_UNICODE);die;
        }
        $user_pwd=$request->post('user_pwd');
//        $user_email=$request->post('user_email');
        $user_time=time();
        $user= new Pusers();
        $user->user_name=$user_name;
        $user->password=password_hash($user_pwd,PASSWORD_BCRYPT);
        $user->reg_time=$user_time;
        $res=$user->save();
        if($res){
            $data=[
                "errno"     =>0,
                "msg"       =>"ok"
            ];
            echo json_encode($data);
        }else{
            $data=[
                "errno"     => 500001,
                "msg"       => "no",
                "data"      =>[
                    'error' => '入库失败',
                ]
            ];
            echo json_encode($data,JSON_UNESCAPED_UNICODE);
        }

    }
    //登录
    public function login(Request $request)
    {
        $name=$request->post('name');
        $pwd=$request->post('pwd');
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
    //获取用户详细信息
    public function userInfo()
    {
        $time=time();
        $user_id=request()->get('user_id');
        $access_token=request()->get('access_token');
        $token=Token::where('access_token',$access_token)->first();
        if($time>$token->ex_time){
            $data=[
                "errno"     =>200002,
                "msg"       =>"no",
                "data"      =>[
                    'error' => 'access_token已过期',
                ]
            ];
            echo json_encode($data,JSON_UNESCAPED_UNICODE);
        }
        $userInfo=Pusers::where('user_id',$token->uid)->first();
        $data=[
            'errno'      => '0',
            'msg'      => 'ok',
            'data'     =>[
                "userInfo"   =>$userInfo,
            ]
        ];
       return json_encode($data);
    }
    //加密测试
    public function test2(){
        //加密
        $data="hello woman";
        $method='AES-256-CBC';
        $key="1911";
        $iv='aaaabbbbccccdddd';
        $d=openssl_encrypt($data,$method,$key,OPENSSL_RAW_DATA ,$iv);
        dump($d);
        //解密
        $a=openssl_decrypt($d,$method,$key,OPENSSL_RAW_DATA ,$iv);
        dd($a);
    }
    /*
     * 解密www传过来的值
     * */
    public function dec(){
        $method="AES-256-CBC";
        $key="1911";
        $option=OPENSSL_RAW_DATA;
        $iv='aaaabbbbccccdddd';
//        $data=request()->post();
        $enc_data=request()->post('data');
        $dec_64b=base64_decode($enc_data);
        //对称解密
        $code=openssl_decrypt($dec_64b,$method,$key,$option,$iv);
        echo '解密后的内容：   '.$code;
    }
    /*
     * 非对称加密
     * */
    public function enc(){
        $data=request()->post('data');
        $signature=request()->post('key');
        //base64解密
        $b64_decode=base64_decode($data);
        //秘钥解密
        $key=file_get_contents(storage_path('key/priv.php'));
        //调用公钥
        $pub_key=file_get_contents(storage_path('key/www_pub.php'));
        openssl_private_decrypt($b64_decode,$dec_data,$key);
        //验证签名
        $int=openssl_verify($data,$signature,$pub_key);
        //加密
        $info="收到收到，你也好，我是api，以后你会经常调用我的";
        if($int==0){
            $info= '签名错误';
        }
        //回复信息
        $keys=file_get_contents(storage_path('key/www_priv.php'));
        openssl_private_encrypt($info,$crypted,$keys);
        //base64加密
        $b64_info=base64_encode($crypted);
        echo $b64_info;
    }
    /*
     * headers传参测试
     * */
    public function headers(){
        $data=$_SERVER;
        dd($data);
    }





}

