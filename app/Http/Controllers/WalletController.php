<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\RequestMatcherInterface;
use Illuminate\Support\Facades\Auth;


use App\Wallet;
class WalletController extends Controller
{
    //
    public function walletAdd(Request $request){
        $inputData = $this->check_valiable($request,['name','money']);
        if(!$inputData['result']){
            return $inputData['data'];
        }
        $result = $this->createWallet(
            Auth::user()->id,
            $inputData['data']['name'],
            $inputData['data']['money']
        );
        if($result){
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
    public function walletList(Request $request){
        $walletModel = new Wallet;
        $walletModelData = $walletModel->where('user_id',Auth::user()->id)->get();
        return response()->json([
            'msg'=>'list success',
            'state'=>1,
            'data'=>$walletModelData
        ]);

    }
    public function walletUpdate(Request $request){
        $walletModel = new Wallet;
        $inputData = $this->check_valiable($request,['id','money','name']);
        $walletModelData = $walletModel->find($inputData['data']['id']);
        if($walletModelData->user_id != Auth::user()->id){
            return response()->json([
                'msg'=>'该钱包非当前用户创建',
                'state'=>0,
            ]);
        }
        $walletModelData->money = $inputData['data']['money'];
        $walletModelData->name = $inputData['data']['name'];
        if($walletModelData->save()){
            return response()->json([
                'msg'=>'list success',
                'state'=>1,
                'data'=>$walletModelData
            ]);
        };
    }
    private function check_valiable($request,$type){
        $name = $request->input('name');
        $id = $request->input('walletId');
        $money = $request->input('money');
        $result = [
            'result'=>false,
            'data'=>[]
        ];
        //addWallet
        if(in_array('name',$type)){
            if(!$name){
                $result['result'] = false;
                $result['data'] = response()->json([
                    'msg'=>'数据输入不合法',
                    'state'=>0
                ]);
                return $result;
            }else{
                $result['result'] = true;
                $result['data']['name'] = $name;
            }
        }
        if(in_array('id',$type)){
            if(!$id){
                $result['result'] = false;
                $result['data'] = response()->json([
                    'msg'=>'数据输入不合法',
                    'state'=>0
                ]);
                return $result;
            }else{
                $result['result'] = true;
                $result['data']['id'] = $id;
            }
        }
        if(in_array('money',$type) ){
            if(!$money){
                $result['result'] = false;
                $result['data'] = response()->json([
                    'msg'=>'数据输入不合法',
                    'state'=>0
                ]);
                return $result;
            }else{
                $money +=0;//转换为数字
                if($money>99999999 || $money<-9999999){
                    $result['result'] = false;
                    $result['data'] = response()->json([
                        'msg'=>'数值超出范围',
                        'state'=>0
                    ]);
                    return $result;
                }
                $result['result'] = true;
                $result['data']['money'] = $money;
            }
        }
        return $result;
    }
    public function createWallet($userid,$name='',$money=0){
        $walletModel = new Wallet;
        $walletModel->name = $name;
        $walletModel->money = $money;
        $walletModel->user_id = $userid;
        $result = $walletModel->save();
        return $result;
    }
}
