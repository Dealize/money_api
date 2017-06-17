<?php

namespace App\Http\Controllers;

use App\IncomeCategory;
use App\IncomeSecondCategory;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class IncomeCategoryController extends Controller
{
    public function firstAdd(Request $request)
    {
        $firstCategory = new IncomeCategory;

        $inputData = $this->check_valiable($request);
        if(!$inputData['result']){
            return $inputData['data'];
        }
        $firstCategory->name  = $inputData['data']['name'];
        $firstCategory->user_id = Auth::user()->id;
        $result = $firstCategory->save();
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
    public function firstUpdate(Request $request){
        $firstCategory = new IncomeCategory;
        $inputData = $this->check_valiable($request,1);
        if(!$inputData['result']){
            return $inputData['data'];
        }
        $id = $inputData['data']['id'];
        $name = $inputData['data']['name'];
        $modelData = $firstCategory->find($id);
        if(Auth::user()->id!=$modelData->user_id){
            return response()->json([
                'msg'=>'非创建者不能修改',
                'state'=>0
            ]);
        }
        $modelData->name = $name;
        if($modelData->save()){
            return response()->json([
                'msg'=>'update success',
                'state'=>1
            ]);
        }
    }
    public function secondAdd(Request $request){
        $firstCategory = new IncomeCategory;
        $secondCategory = new IncomeSecondCategory;
        $inputData = $this->check_valiable($request,2);
        if(!$inputData['result']){
            return $inputData['data'];
        }
        $firstId = (int)$inputData['data']['firstId'];
        $name = $inputData['data']['name'];
        $secondCategory->name = $name;
        $secondCategory->firstCategory_id =(int)$firstId;
        $secondCategory->user_id = Auth::user()->id;
        $secondCategory->valiable = 'true';
        $result = $secondCategory->save();
        dd($result);
        if($result){
            return response()->json([
                'msg'=>'save success',
                'state'=>1
            ]);
        }
    }
    public function getInfo(Request $request){
        $firstCategory = new IncomeCategory;
        $secondCategory = new IncomeSecondCategory;
        $finalData = [];
//        dd(Auth::user()->id);
//        $secondCategoryData =
        $firstCategoryData = $firstCategory->where('user_id',Auth::user()->id)->get();

        foreach ($firstCategoryData as $item){
            $secondCategoryData = $secondCategory->where('firstCategory_id',$item->id)->get();
            $item['secondCategory'] = $secondCategoryData;
        }
        return response()->json([
            'state'=>1,
            'data'=>$firstCategoryData
        ]);
    }
    private function check_valiable($request,$type=0){
        $name = $request->input('name');
        $id = $request->input('id');
        $firstId = $request->input('firstId');
        $result = [
            'result' => false,
            'data'=>[]
        ];
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
        if($type==1){
            if(!$id){
                $result['result'] = false;
                $result['data'] = response()->json([
                    'msg'=>'数据输入不合法',
                    'state'=>0
                ]);
                return $result;
            }else{
                $result['data']['id'] = $id;
            }
        }
        if($type==2){
            if(!$firstId){
                $result['result'] = false;
                $result['data'] = response()->json([
                    'msg'=>'数据输入不合法',
                    'state'=>0
                ]);
                return $result;
            }else{
                $result['data']['firstId'] = $firstId;
            }
        }
        return $result;
    }
}
