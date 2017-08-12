<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Bill;
use App\Wallet;
use Illuminate\Support\Facades\Auth;


class BillController extends Controller
{
    //
    public function billAdd(Request $request){
        $billModel = new Bill;
        $walletModel = new Wallet;
        $inputData = $this->check_valiable($request,['add']);
        if(!$inputData['result']){
            return $inputData['data'];
        }
        $beginTime = $inputData['data']['beginTime'];
        //由于间隔最少都要是一天。所以这里要给结束时间+1天
        $endTime = $inputData['data']['endTime'];
        $day = $this->get_intervalDay_byTime($beginTime,$endTime);
//        $day = (int)floor(($endTime - $beginTime)/86400);
        $beginTime = date('Y-m-d',$beginTime);
        $endTime = date('Y-m-d',$endTime);
        $billModel->beginTime = $beginTime;
        $billModel->endTime = $endTime;
        $billModel->days = $day;
        $money = $inputData['data']['money'];
        $billModel->money = $money;
        $billModel->isWorth = $inputData['data']['isWorth'];
        $billModel->comment = $inputData['data']['comment'];
        $billModel->category_id = $inputData['data']['categoryId'];
        $billModel->daily_cost = bcdiv($money,$day,2);
        $walletData = $walletModel->where('user_id',Auth::user()->id)->first();
        if($walletData==null){
            return response()->json([
                'msg'=>'未找到账户信息，需要在更多设置里进行账户信息初始设置。',
                'state'=>0
            ]);
        }
        $billModel->wallet_id = $walletData->id;
        $billModel->user_id = Auth::user()->id;
        $billType = $inputData['data']['billType'];
        $billModel->billType = $billType;
        $walletMoney = $walletData->money;
        //1 代表支出
        //2 代表收入
        if($billType=="1"){
            $num = bcsub($walletMoney,$money);
        }else if($billType=='2'){
            $num = bcadd($walletMoney,$money);
        }
        $walletData->money = $num;
        $walletUpdateResult = $walletData->save();
        $result = $billModel->save();
        if($result && $walletUpdateResult){
            return response()->json([
                'msg'=>'save success',
                'state'=>1
            ]);
        }else{
            return response()->json([
                'msg'=>'save failed',
                'state'=>0
            ]);
        }

    }
    private function get_intervalDay_byTime($beginTime,$endTime){
        $day =  ($endTime-$beginTime)/86400+1;
        return $day;
    }
    private function check_valiable($request,$type){
        $money = $request->input('money');
        $billType = $request->input('billType');
        $isWorth = $request->input('isWorth');
        $comment = $request->input('comment');
        $beginTime = $request->input('beginTime');
        $endTime = $request->input('endTime');
        $category_id = $request->input('categoryId');
//        $wallet_id = $request->input('walletId');
        $result = [
            'result'=>false,
            'data'=>[]
        ];
        if(in_array('add',$type)){
            if($money&&$billType&&$isWorth&&$beginTime&&$endTime&&$category_id){
                $result['result'] = true;
                $result['data']['money'] = $money;
                $result['data']['billType'] = $billType;
                $result['data']['isWorth'] = $isWorth;
                $beginTime = strtotime($beginTime);
                $endTime = strtotime($endTime);
                $result['data']['beginTime'] = $beginTime;
                $result['data']['endTime'] = $endTime;
                $result['data']['categoryId'] = $category_id;
//                $result['data']['walletId'] = $wallet_id;
                $result['data']['comment'] = $comment;
                if(is_numeric($money)){
                    if($money<0){
                        $result['result'] = false;
                        $result['data'] = response()->json([
                            'msg'=>'金额不能为负。',
                            'state'=>0
                        ]);
                        return $result;
                    }
                }else{
                    $result['result'] = false;
                    $result['data'] = response()->json([
                        'msg'=>'金额必须为数字。',
                        'state'=>0
                    ]);
                    return $result;
                }


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
