<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\api\AuthController;
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

});
