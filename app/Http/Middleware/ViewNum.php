<?php

namespace App\Http\Middleware;

use Closure;
use App\Model\Token;
use App\Puser;
use Illuminate\Support\Facades\Redis;

class ViewNum
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
        $token=$request->get('token');
        if(!empty($token)){
            //查询用户id
            $user_id=Token::select('uid')->where('access_token',$token)->first();
            if(empty($user_id)){
                $data=[
                    'errno' => '20001',
                    'msg'   => '未授权22'
                ];
                return json_encode($data,JSON_UNESCAPED_UNICODE);
            }
            //将访问信息存入redis
            $url=$request->path();
            $data=[
                'url' => $url,
                'incr' => '1'
            ];
            $key='h:view_count'.'_'.$user_id->uid;
            $res=Redis::hgetall($key);
            //判断
            if(!empty($res)){
                //如果已经有就自增
                Redis::hincrby($key,'incr',1);
            }else{
                //没有就存redis
                Redis::hMset($key,$data);
            }
        }else{
            $data=[
                'errno' => '20001',
                'msg'   => '未授权11'
            ];
            return json_encode($data,JSON_UNESCAPED_UNICODE);
        }
        return $next($request);
    }
}
