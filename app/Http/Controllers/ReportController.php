<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Bill;
use App\Wallet;
use Illuminate\Support\Facades\Auth;


class ReportController extends Controller
{
    public function daily(Request $request){
        $this->getBillInfo($request);
//        $todayTime = date('Y-m-d h:i:s');
//        $todayTime = date_create();
//
//        date_time_set($todayTime,0,0,0);
//        dd($todayTime);
//        $outlayData = $billData->where([
//           ['billType','=',"1"]
//        ])->get();
//        dd($todayTime);
//        $incomeData = $billData->where([
//            ['billType','=',"0"]
//        ])->get();
////        dd($incomeData);
////        $outlayData_sum = $outlayData->sum('')
        $billData_data = $billData->get();
        $billData_count = $billData->count();
        $billData_sum = $billData->sum('daily_cost');
        $billData_money = $this->getWalletInfo($request);
        return response()->json([
            'msg'=>'',
            'state'=>1,
            'data'=>[
                'sum'=>$billData_sum,
                'count'=>$billData_count,
                'money'=>$billData_money
//                'list'=>$billData_data
            ]
        ]);
    }

    private function getWalletInfo(Request $request){
        $walletModel = new Wallet;
        $walletData = $walletModel->where([
            ['user_id','=',Auth::user()->id]
        ])->get();
        dd($walletData[0]['money']);
        return [
            'wallet'=>$walletData['money']
        ];
    }
    private function getCostInfo(Request $request){
        $billModel = new Bill;


    }
    private function getBillInfo(Request $request){
        $billModel = new Bill;
        $todayTime = date_create();
        date_time_set($todayTime,0,0,0);
        $beginTime = date_format($todayTime,"Y/m/d H:i:s");
        date_time_set($todayTime,23,59,59);
        $endTime = date_format($todayTime,"Y/m/d H:i:s");
        $billData = $billModel->where([
            ['user_id','=',Auth::user()->id],
            ['created_at','>=',$beginTime],
            ['created_at','<=',$endTime]
        ]);
        $outlayData = $billData->where([
            ['billType','=','1']
        ])->get();
        $incomeData = $billData->where([
            ['billType','=','0']
        ])->get();
        $outlay_allMoney = $outlayData->sum('money');
        $income_allMoney = $incomeData->sum('money');


        dd($outlay_allMoney);


    }
}
