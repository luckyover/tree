<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\api\AuthController;
use App\Http\Controllers\api\CartController;
use App\Http\Controllers\api\AdminController;
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
// Route::get('/user', function (Request $request) {
//     dd(1);
//     return $request->user();
//     // $credentials['email'] = 1;
//     // $credentials['password'] =1;

//     // if(Auth::attempt($credentials)){
//     //     $user = Auth::user();

//     //     $tokenResult = $user->createToken('authToken')->plainTextToken;
//     //     dd($tokenResult );
//     // }

// });
Route::post('register', [AuthController::class, 'register']);
Route::post('login', [AuthController::class, 'login']);


Route::middleware(['auth:sanctum'])->group(function () {
    Route::post('logout', [AuthController::class, 'logout']);
    Route::get('get-auth', [AuthController::class, 'getAuth']);
    Route::post('delete-auth', [AuthController::class, 'delAuth']);
    Route::post('get-cart', [CartController::class, 'getCart']);
    Route::post('add-cart', [CartController::class, 'addCart']);
    Route::post('remove-cart', [CartController::class, 'removeCart']);
    Route::post('update-cart', [CartController::class, 'updateCart']);
    Route::post('order-cart', [CartController::class, 'orderCart']);
    Route::post('his-cart', [CartController::class, 'hisCart']);


    Route::post('get-admin', [AdminController::class, 'getAdmin']);
    Route::post('order-update-admin', [AdminController::class, 'orderUpdateAdmin']);
    Route::post('get-oder-detail-admin', [AdminController::class, 'orderDetailAdmin']);
    

});
