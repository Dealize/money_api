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
//Route::middleware('auth:api')->post('/category/first/add',function(){
//    dd(123);
//});


Route::middleware('auth:api')->get('/home', 'HomeController@index')->name('home');

