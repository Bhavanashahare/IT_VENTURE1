<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\UserController;
use App\Http\Controllers\API\MobileController;


/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/
// Route::post('/signup', 'MobileController@signup');
Route::post('/signup',[MobileController::class,'signup']);

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});


Route::post('login',[UserController::class,'loginUser']);


Route::group(['middleware' => 'auth:sanctum'],function(){
    Route::get('user',[UserController::class,'userDetails']);
    Route::get('logout',[UserController::class,'logout']);
  //  Route::get('signup',[UserController::class,'signup']);
//   Route::post('/signup', 'AuthController@signup');

    //Route::post('generate-otp',[UserController::class,'generateOTP']);
    Route::post('/password/otp-verify', 'MobileController@forgetOtpVerify'
);

});
