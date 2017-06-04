<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Bill;
use Illuminate\Support\Facades\Auth;


class ReportController extends Controller
{
    public function daily(Request $request){
        $billModel = new Bill;
        $todayTime = date('Y-m-d h:i:s');
        $billData = $billModel->where([
            ['user_id','=',Auth::user()->id],
            ['beginTime','<=',(string)$todayTime],
            ['endTime','>=',(string)$todayTime]
        ]);
        $billData_data = $billData->get();
        $billData_count = $billData->count();
        $billData_sum = $billData->sum('daily_cost');
        return response()->json([
            'msg'=>'',
            'state'=>1,
            'data'=>[
                'sum'=>$billData_sum,
                'count'=>$billData_count,
//                'list'=>$billData_data
            ]
        ]);
    }
}
