<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\WalletController;


class MyAuthController extends Controller
{
    public function __construct(){
        $this->middleware('guest', ['except' => ['logout', 'getLogout']]);
    }
    public function login(Request $request){
        $email = $request->input('email');
        $password = $request->input('password');
        $remember = true;
        if(Auth::attempt(['email'=>$email,'password'=>$password],$remember)){
            return response()->json([
                'state'=>1,
                'msg'=>'login success!'
            ]);
//            redirect()->intended('dashboard');
        }else{
            return response()->json([
                'state'=>0,
                'msg'=>'login failed'
            ]);
        }
    }

    public function register(Request $request){
        dd(555);
        $name = $request->input('name');
        $email = $request->input('email');
        $password = $request->input('password');
        if((!$email) || (!$password)){
            return response()->json([
               'state'=>0,
                'msg'=>'缺少参数'
            ]);
        }

        if(User::where('email',$email)->count()!=0){
            return response()->json([
                'state'=>0,
                'msg'=>'用户已经存在'
            ]);
        };

        $result =User::create([
            'name'=>$name,
            'email'=>$email,
            'password'=>bcrypt($password)
        ]);
        return response()->json([
            'state'=>1,
            'msg'=>'注册成功'
        ]);
    }


    public function logout(Request $request){
        Auth::logout();
        return response()->json([
            'msg'=>'logout',
            'state'=>1
        ]);
    }
}
