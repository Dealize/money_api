<?php

namespace App\Http\Controllers;

use App\User;
use App\Http\Controllers\WalletController;
use App\Http\Controllers\ReportCurrencyController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class MyAuthController extends Controller
{
//    public function __construct(){
//        $this->middleware('guest', ['except' => ['logout', 'getLogout']]);
//    }
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
        $userModel = new User;
        $name = $request->input('name');
        $email = $request->input('email');
        $password = $request->input('password');
        $walletCtrl = new WalletController();
        $currencyCtrl = new ReportCurrencyController();
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
        $userId = $result->id;
        $walletResult = $walletCtrl->createWallet($userId);
        if($result){
            if($walletResult){
                return response()->json([
                    'state' =>1,
                    'msg'=>'注册成功！'
                ]);
            }else{
                return response()->json([
                    'state' =>1,
                    'msg'=>'注册成功,需要手动创建账户余额'
                ]);
            }
        };
        $reportCurrencyResult = $currencyCtrl->createAccount($userId);
    }


    public function logout(Request $request){
        Auth::logout();
        return response()->json([
            'msg'=>'logout',
            'state'=>1
        ]);
    }
}
