<?php

namespace App\Http\Controllers;

use App\Model\Token;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redis;
use App\Model\Pgoods;
use App\Model\Pusers;
class GoodsController extends Controller
{
    public function index(){
        Redis::LPOP('list');
        $lens=Redis::llen('list');
        dump('111');
        dd($lens);
    }
    /*
   * 商品详情
   * */
    public function show(Request $request)
    {
        //根据商品id查询商品数据
        $id=$request->get('goods_id');
        $key="goods_id:".$id;
        //从redis中获取商品信息
        $goods_info=Redis::hgetall($key);
        if(empty($goods_info)){
            echo '数据库';
            //根据商品id查询商品数据
            $goods_info=Pgoods::where('goods_id',$id)->first()->toArray();
            $goods_info['visit_num']='1';
            //将查询到的数据存入redis
            Redis::hmset($key,$goods_info);
        }
        $goods_info['visit_num']=Redis::hincrby($key,$goods_info['visit_num'],1);
        return $goods_info;
    }
    /*
     * 黑名单
     * */
    public function blacklist()
    {
        $user_id=request()->get('user_id');
        $key="blacklist_".$user_id;
        $data=Redis::smembers($key);
        if($data){
            echo json_encode('用户已拉黑',JSON_UNESCAPED_UNICODE);
        }else{
            //加入黑名单blacklist
            $token=Token::select('access_token')->where('uid',$user_id)->first()->toArray();
            Redis::sadd($key,$token['access_token']);
            echo json_encode('用户拉黑成功',JSON_UNESCAPED_UNICODE);
        }
    }
    /*
     * 签到
     * */
    public function Usersign()
    {
        $user_id=request()->get('user_id');
        $key="qiandao_".date('ymd');
        $time=time();
        $data=Redis::zrange($key,$time);
        if($data){
            echo json_encode('已经签到',JSON_UNESCAPED_UNICODE);
        }else{
            Redis::zadd($key,$time,$user_id);
            echo json_encode('签到成功',JSON_UNESCAPED_UNICODE);
        }
    }
    //测试
    public function test(){
        echo 111;
    }
    public function test1(){
        echo '222';
    }



}
