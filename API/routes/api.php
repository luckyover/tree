<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
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
Route::get('/user', function (Request $request) {
    dd(1);
    return $request->user();
    // $credentials['email'] = 1;
    // $credentials['password'] =1;
   
    // if(Auth::attempt($credentials)){
    //     $user = Auth::user();
       
    //     $tokenResult = $user->createToken('authToken')->plainTextToken;
    //     dd($tokenResult );
    // }
   
});
Route::middleware(['auth:sanctum'])->group(function () {
    Route::post('/test', function (Request $request) {
        $response = [
            'message' => 'okkkk',
            'errors' => [],
            'status' =>200
        ];
        // return response()->json($response, $http_code);
        return response()->json($response);
       
    });
});
Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    // return $request->user();
});

