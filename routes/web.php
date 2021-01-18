<?php

use Illuminate\Support\Facades\Route;

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
Route::options('/modules/3/screens',function(){
    header("Access-Control-Allow-Origin: *");
    header("Access-Control-Allow-Headers: Content-Type");
   
    return '1';
});
Route::post('/modules/3/screens',function(){
    header("Access-Control-Allow-Origin: *");
    header("Access-Control-Allow-Headers: Content-Type");
    return json_encode(array("data"=>["width"=> 1350,
    "height"=> 18,
    "updated_at"=> "2020-09-05 01:48:17",
    "created_at"=> "2020-09-05 01:48:17",
    "id"=> 588]));

});

Route::get('/salut', function () {
    return view('welcome');

});
