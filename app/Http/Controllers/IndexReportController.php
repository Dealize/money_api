<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Bill;
use App\Wallet;
use Illuminate\Support\Facades\Auth;


class indexReportController extends Controller
{
    public function index(Request $request){
        $indexInfo = $this->getBaseCostInfo($request);
        $walletInfo = $this->getWalletInfo($request);
        $todayInfo = $this->getTodayCostInfo($request);
        $monthInfo = $this->getMonthCostInfo($request);
        return response()->json([
            'msg'=>'',
            'state'=>1,
            'data'=>[
                'indexInfo'=>$indexInfo,
                'walletInfo'=>$walletInfo,
                'todayInfo'=>$todayInfo,
                'monthInfo'=>$monthInfo
            ]
        ]);
    }
    private function getBaseCostInfo(Request $request){
        $billModel = new Bill;
        $todayTime = date_create();
        date_time_set($todayTime,0,0,0);
        $beginTime = date_format($todayTime,"Y/m/d H:i:s");
        date_time_set($todayTime,23,59,59);
        $endTime = date_format($todayTime,"Y/m/d H:i:s");
        $incomeData = $billModel->where([
            ['user_id','=',Auth::user()->id],
            ['beginTime','<=',$beginTime],
            ['endTime','>=',$endTime],
            ['billType','=','2']
        ])->get();
        $outlayData = $billModel->where([
            ['user_id','=',Auth::user()->id],
            ['beginTime','<=',$beginTime],
            ['endTime','>=',$endTime],
            ['billType','=','1']
        ])->get();

        $outlayData_money =0;
        $incomeData_money = 0;
        foreach($outlayData as $outlayDataItem){
            $beginTime =$outlayDataItem['beginTime'];
            $endTime = $outlayDataItem['endTime'];
            $intervalDays = $this->get_intervalDays($beginTime,$endTime);
            $money = $outlayDataItem['money'];
            $outlayData_money += bcdiv($money,$intervalDays,5);
        }
        foreach($incomeData as $incomeDataItem){
            $beginTime =$incomeDataItem['beginTime'];
            $endTime = $incomeDataItem['endTime'];
            $intervalDays = $this->get_intervalDays($beginTime,$endTime);
            $money = $incomeDataItem['money'];
            $incomeData_money += bcdiv($money,$intervalDays,5);
        }
        $outlayData_money = round($outlayData_money,2);
        $incomeData_money = round($incomeData_money,2);

        return [
            'outlayData_money'=>$outlayData_money,
            'incomeData_money'=>$incomeData_money,
        ];

    }
    private function getWalletInfo(Request $request){
        $walletModel = new Wallet;
        $walletData = $walletModel->where([
            ['user_id','=',Auth::user()->id]
        ])->get();
        //先默认只有1个钱包
        return [
            'remainAll'=>$walletData[0]['money']
        ];
    }
    private function getTodayCostInfo(Request $request){
        $billModel = new Bill;
        $todayTime = date_create();
        date_time_set($todayTime,0,0,0);
        $beginTime = date_format($todayTime,"Y/m/d H:i:s");
        date_time_set($todayTime,23,59,59);
        $endTime = date_format($todayTime,"Y/m/d H:i:s");
        $incomeData = $billModel->where([
            ['user_id','=',Auth::user()->id],
            ['created_at','<',$beginTime],
            ['created_at','>',$endTime],
            ['billType','=','2']
        ])->sum('money');
        $outlayData = $billModel->where([
            ['user_id','=',Auth::user()->id],
            ['created_at','>',$beginTime],
            ['created_at','<',$endTime],
            ['billType','=','1']
        ])->sum('money');


        return [
            'incomeNum'=>$incomeData,
            'outlayNum'=>$outlayData,
        ];

    }
    private function getMonthCostInfo(Request $request){
        $_year = date('Y');
        $_month = date('m');
        $_lastDay = date('t');
        $billModel = new Bill;
        $todayTime = date_create();
        date_date_set($todayTime,$_year,$_month,1);
        date_time_set($todayTime,0,0,0);
        $beginTime = date_format($todayTime,"Y/m/d H:i:s");
        date_date_set($todayTime,$_year,$_month,$_lastDay);
        date_time_set($todayTime,23,59,59);
        $endTime = date_format($todayTime,"Y/m/d H:i:s");
        $incomeData = $billModel->where([
            ['user_id','=',Auth::user()->id],
            ['created_at','>',$beginTime],
            ['created_at','<',$endTime],
            ['billType','=','2']
        ])->sum('money');
        $outlayData = $billModel->where([
            ['user_id','=',Auth::user()->id],
            ['created_at','>',$beginTime],
            ['created_at','<',$endTime],
            ['billType','=','1']
        ])->sum('money');


        return [
            'incomeNum'=>$incomeData,
            'outlayNum'=>$outlayData,
        ];

    }
    private function get_intervalDays($beginTime,$endTime){
        $time1 = strtotime($beginTime);
        $time2 = strtotime($endTime);
        $resultVal = ($time2-$time1)/3600/24;
        //如果两个时间差为0，说明其时间间隔也是1天，故做下面这种赋值
        if($resultVal==0){
            $resultVal = 1;
        }
        return $resultVal;
    }
}
