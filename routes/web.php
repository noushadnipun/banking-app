<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BankingController;
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
    return redirect('home');
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
Route::post('/login', [BankingController::class, 'login']);
Route::post('/register', [BankingController::class, 'createUser']);
Route::group(['middleware' => 'auth'], function(){
    Route::get('/transactions', [BankingController::class, 'showTransactions']);
    Route::get('/deposit', [BankingController::class, 'showDeposits']);
    Route::post('/deposit', [BankingController::class, 'deposit']);
    Route::get('/withdrawal', [BankingController::class, 'showWithdrawals']);
    Route::post('/withdrawal', [BankingController::class, 'withdrawal']);
});


