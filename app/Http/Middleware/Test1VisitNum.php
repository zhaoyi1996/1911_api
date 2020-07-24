<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Redis;

class Test1VisitNum
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
        $url=$request->path();
        $key="num_".date('ymd');
        if(!empty($url)){
            Redis::zincrby($key,1,$url);
        }else{
            Redis::zadd($key,$url,1);
        }
        return $next($request);
    }
}
