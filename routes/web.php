<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MailController;
use App\Http\Controllers\OAuthController;

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
  /*   return view('welcome'); */
    return redirect()->route('home') ;
});

Route::prefix('/')->group(function(){
    Route::view('home', 'home')->name('home');
    Route::post('/get-token', [OAuthController::class, 'doGenerateToken'])->name('generate.token');
    Route::get('/get-token', [OAuthController::class, 'doSuccessToken'])->name('token.success');
    Route::post('/send', [MailController::class, 'doSendEmail'])->name('send.email');
});
