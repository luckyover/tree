<?php

use Illuminate\Support\Facades\Route;
use App\Utility\Database\Facades\Dao;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    $params['json'] = 1;
    dd(Dao::execute('SPC_TEST_ACT1', $params));
    return view('welcome');
});
