<?php

namespace App\Http\Middleware;

use Closure;
use App\Model\Token;
class VerifyToken
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        //接收token
        $access_token=$request->get('access_token');
        if($access_token){
            //查询token是否存在
            $token=Token::where('access_token',$access_token)->first();
            if(!empty($token)){
                dd(222);
                $time=time();
                //判断access_token是否失效
                if($time-$token->ex_time>7200){
                    $data=[
                        'errno' => '20003',
                        'msg'   => '授权失败1'
                    ];
                }else{
                    $data=[
                        'errno' => '0',
                        'msg'   => 'yes'
                    ];
                    return $next($request);
                }
            }else{
                $data=[
                    'errno' => '20003',
                    'msg'   => '授权失败'
                ];
            }
        }else{
            $data=[
                'errno' => '20001',
                'msg'   => '未授权'
            ];
        }
        echo json_encode($data,JSON_UNESCAPED_UNICODE);

    }
}
