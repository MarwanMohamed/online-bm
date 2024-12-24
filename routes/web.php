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
Route::get('/insurance/getVhlModels/{id}', [InsuranceController::class, 'getVhlModels']);
Route::get('/insurance/getPrice/{id}', [InsuranceController::class, 'getPrice']);
Route::get('/insurance/allowQid', [InsuranceController::class, 'allowQid']);
Route::post('/insurance/confirm', [InsuranceController::class, 'confirm']);


Route::get('/insurance/comprehensive', [InsuranceController::class, 'comprehensive']);


Route::get('renew', [RenewController::class, 'renew']);
Route::get('renew/view', [RenewController::class, 'renewView']);
Route::post('renew/confirm', [RenewController::class, 'renewConfirm']);
Route::post('renew/getPolicyDetails', [RenewController::class, 'getPolicyDetails']);


Route::get('payment/quickpay', [QuickPayController::class, 'quickPay']);
Route::post('payment/select-payment', [QuickPayController::class, 'selectPayment']);
Route::get('payment/selectpayment', [QuickPayController::class, 'selectPayment']);
Route::post('payment/getPolicyPayDetails', [QuickPayController::class, 'getPolicyPayDetails']);
Route::post('payment/qcbankpayment', [QuickPayController::class, 'qcbankpayment']);
Route::post('payment/dohabankpayment', [QuickPayController::class, 'dohabankpayment']);

Route::get('/check-new-recording', function () {
    // Fetch the latest recording from the database
    $latestRecording = \App\Models\Insurance::latest()->first();
    // Check if there is a new recording
    $lastChecked = session('last_checked_recording_id', null);
    $newRecording = $latestRecording->id != $lastChecked;

    // Return the response
    return response()->json([
        'new_recording' => $newRecording,
        'recording_id' => $latestRecording ? $latestRecording->id : null,
    ]);
});

Route::get('/update-last-checked/{id}', function ($id) {
    session(['last_checked_recording_id' => $id]);
});