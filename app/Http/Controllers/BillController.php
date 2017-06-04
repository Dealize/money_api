<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Bill;
use Illuminate\Support\Facades\Auth;


class BillController extends Controller
{
    //
    public function billAdd(Request $request){
        $billModel = new Bill;
        $inputData = $this->check_valiable($request,['add']);
        if(!$inputData['result']){
            return $inputData['data'];
        }
        $beginTime = $inputData['data']['beginTime'];
        $endTime = $inputData['data']['endTime'];
        $day = (int)floor(($endTime - $beginTime)/86400);
        if($day==0){
            $day = 1;
        }
        $beginTime = date('Y-m-d',$beginTime);
        $endTime = date('Y-m-d',$endTime);

        $billModel->beginTime = $beginTime;
        $billModel->endTime = $endTime;
        $billModel->days = $day;
        $money = $inputData['data']['money'];
        $billModel->money = $money;
        $billModel->daily_cost = bcdiv($money,$day,2);
        $billModel->isWorth = $inputData['data']['isWorth'];
        $billModel->comment = $inputData['data']['comment'];
        $billModel->category_id = $inputData['data']['categoryId'];
        $billModel->wallet_id = $inputData['data']['walletId'];
        $billModel->billType = $inputData['data']['billType'];
        $billModel->user_id = Auth::user()->id;
        $result = $billModel->save();
        if($result){
            return response()->json([
                'msg'=>'save success',
                'state'=>1
            ]);
        }
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
                $beginTime = strtotime($beginTime);
                $endTime = strtotime($endTime);
                $result['data']['beginTime'] = $beginTime;
                $result['data']['endTime'] = $endTime;
                $result['data']['categoryId'] = $category_id;
                $result['data']['walletId'] = $wallet_id;
                $result['data']['comment'] = $comment;
                if(!($beginTime && $endTime)){
                    $result['result'] = false;
                    $result['data'] = response()->json([
                        'msg'=>'传入时间格式不正确',
                        'state'=>0
                    ]);
                    return $result;
                }
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
