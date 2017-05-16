<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Bill;


class BillController extends Controller
{
    public  function  billAdd(Request $request){
        $billModel = new Bill;
        $inputData = $this->check_valiable($request);
        dd($billModel->id);
    }


    private function  check_valiable($request,$type=0){
        $money = $request->input('money');
        $categoryId = $request->input('categoryId');
        $billType = $request->input('billType');
        $comment = $request->input('comment');
        $result = [
            'result'=>false,
            'data'=>[]
        ];
        if(!$money){
            $result['data'] = response()->json([
                'msg'=>'缺少参数',
                'state'=>0
            ]);
            return $result;
        }else{
            $result['result'] = true;
            $result['data']['money'] = $money;
        }
        return $result;

    }
}
