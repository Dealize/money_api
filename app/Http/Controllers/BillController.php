<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Bill;


class BillController extends Controller
{
    //
    public function billAdd(Request $request){
        $inputData = $this->check_valiable($request,['add']);
        $day = $this->get_intervalDay_byTime('2017-5-18','2017-5-20');
        dd($day);
    }
    private function get_intervalDay_byTime($beginTime,$endTime){
        $day =  floor((strtotime($beginTime)-strtotime($endTime))/86400*1.1)*-1;
        dd((int)($day));
    }
    private function check_valiable($request,$type){
        $money = $request->input('money');
        $billType = $request->input('billType');
        $isWorth = $request->input('isWorth');
        $comment = $request->input('comment');
        $beginTime = $request->input('beginTime');
        $endTime = $request->input('endTime');
        $category_id = $request->input('categoryId');
        $wallet_id = $request->input('walletId');
        $result = [
            'result'=>false,
            'data'=>[]
        ];
        if(in_array('add',$type)){
            if($money&&$billType&&$isWorth&&$beginTime&&$endTime&&$category_id&&$wallet_id){
                $result['result'] = true;
                $result['data']['money'] = $money;
                $result['data']['billType'] = $billType;
                $result['data']['isWorth'] = $isWorth;
                $result['data']['beginTime'] = $beginTime;
                $result['data']['endTime'] = $endTime;
                $result['data']['categoryId'] = $category_id;
                $result['data']['walletId'] = $result;
                $result['data']['comment'] = $comment;
                if($beginTime>$endTime){
                    $result['result'] = false;
                    $result['data'] = response()->json([
                        'msg'=>'开始时间不能大于结束时间',
                        'state'=>0
                    ]);
                    return $result;
                }

            }else{
                $result['result'] = false;
                $result['data'] = response()->json([
                    'msg'=>'数据输入不合法',
                    'state'=>0
                ]);
                return $result;
            }
        }


        return $result;
    }
}
