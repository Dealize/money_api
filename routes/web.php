<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

//Route::any('/register',function (){
//    return '123';
//});
Route::post('/hello',function(){
    return '6666';
});


Route::post('/myAuthor/login','MyAuthController@login');
Route::post('/myAuthor/register','MyAuthController@register');


Route::post('/category/first/add','CategoryController@firstAdd')->middleware('myAuth');
Route::post('/category/first/update','CategoryController@firstUpdate')->middleware('myAuth');
Route::post('/category/second/add','CategoryController@secondAdd')->middleware('myAuth');
Route::post('/category/get','CategoryController@getInfo')->middleware('myAuth');

Route::post('/bill/add','BillController@billAdd')->middleware('myAuth');
//Route::post('/bill/list','BillController@billAdd')->middleware('myAuth');
//Route::post('/bill/update','BillController@billAdd')->middleware('myAuth');
//
//Route::post('/cost/list','BillController@billAdd')->middleware('myAuth');
//Route::post('/cost/get','BillController@billAdd')->middleware('myAuth');


Route::post('/wallet/add','WalletController@walletAdd')->middleware('myAuth');
Route::post('wallet/list','WalletController@walletList')->middleware('myAuth');
//Route::post('wallet/detail','WalletController@walletDetail')->middleware('myAuth');




Route::middleware('auth:api')->get('/home', 'HomeController@index')->name('home');


Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
