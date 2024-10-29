<?php
use App\Enums\Feature;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\QuickPayController;
use App\Http\Controllers\RenewController;
use App\Http\Controllers\InsuranceController;

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

Route::get('/', [HomeController::class, 'index']);

Route::get('/insurance/new', [InsuranceController::class, 'index']);
Route::get('/insurance/thirdparty', [InsuranceController::class, 'thirdparty']);
Route::get('/insurance/comprehensive', [InsuranceController::class, 'comprehensive']);


Route::get('renew', [RenewController::class, 'renew']);


Route::get('payment/quickpay', [QuickPayController::class, 'quickPay']);
