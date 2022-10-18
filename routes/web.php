<?php

use App\Http\Controllers\OtpAttemptController;
use App\Http\Controllers\OtpController;
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
    return ['Laravel' => app()->version()];
});
Route::post('/otp', [OtpController::class, 'create'])->name('otp.create');
Route::post('/otp-attempt', [OtpAttemptController::class, 'create'])->name('otpAttempt.create');
