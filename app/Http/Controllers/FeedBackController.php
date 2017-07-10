<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\FeedBack;
use Illuminate\Support\Facades\Auth;

class FeedBackController extends Controller
{
    public function add(Request $request){
        $feedbackModal = new FeedBack;
        $result = [];
        $name = $request->input('name')||"";
        $type = $request->input('type');
        $content = $request->input('content');
        $contact = $request->input('contact');
        $name = $name || '';
        dd($name,$content);
        if(!($name || $type)){
            $result['result'] = false;
            $result['data'] = response()->json([
                'msg'=>'数据输入不全',
                'state'=>0
            ]);
            return $result;
        }
        $feedbackModal->name = $name;
        $feedbackModal->type = $type;
        $feedbackModal->content = $content;
        $feedbackModal->contact = $contact;
        $feedbackModal->user_id = Auth::user()->id;
        $result = $feedbackModal->save();


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




    private function check_valiable($request,$type){
        $name = $request->input('name');
        $type = $request->input('type');
        $content = $request->input('content');
        $contact = $request->input('contact');
        $result = [
            'result'=>false,
            'data'=>[]
        ];
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
        if(in_array('type',$type)){
            if(!$name){
                $result['result'] = false;
                $result['data'] = response()->json([
                    'msg'=>'数据输入不合法',
                    'state'=>0
                ]);
                return $result;
            }else{
                $result['result'] = true;
                $result['data']['type'] = $type;
            }
        }
        if(in_array('content',$type)){
            if(!$name){
                $result['result'] = false;
                $result['data'] = response()->json([
                    'msg'=>'数据输入不合法',
                    'state'=>0
                ]);
                return $result;
            }else{
                $result['result'] = true;
                $result['data']['content'] = $content;
            }
        }
        if(in_array('contact',$type)){
            if(!$name){
                $result['result'] = false;
                $result['data'] = response()->json([
                    'msg'=>'数据输入不合法',
                    'state'=>0
                ]);
                return $result;
            }else{
                $result['result'] = true;
                $result['data']['contact'] = $contact;
            }
        }

        return $result;
    }

}
