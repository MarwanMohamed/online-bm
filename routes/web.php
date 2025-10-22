<?php
use App\Enums\Feature;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\QuickPayController;
use App\Http\Controllers\RenewController;
use App\Http\Controllers\InsuranceController;
use App\Imports\VehiclesImport;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Facades\Excel;

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
Route::get('/insurance/getVhlDetails/{make}', [InsuranceController::class, 'getVhlDetails']);
Route::get('/insurance/getPrice/{id}', [InsuranceController::class, 'getPrice']);
Route::get('/insurance/allowQid', [InsuranceController::class, 'allowQid']);
Route::post('/insurance/confirm', [InsuranceController::class, 'confirm']);


Route::get('/insurance/comprehensive', [InsuranceController::class, 'comprehensive']);


Route::get('renew', [RenewController::class, 'renew']);
Route::get('renew/view', [RenewController::class, 'renewView']);
Route::post('renew/confirm', [RenewController::class, 'renewConfirm']);
Route::post('renew/getPolicyDetails', [RenewController::class, 'getPolicyDetails']);
Route::get('renew/renewal/{insurance}', [RenewController::class, 'generateRenewal']);


Route::get('payment/quickpay', [QuickPayController::class, 'quickPay']);
Route::post('payment/select-payment', [QuickPayController::class, 'selectPayment']);
Route::get('payment/selectpayment', [QuickPayController::class, 'selectPayment']);
Route::post('payment/getPolicyPayDetails', [QuickPayController::class, 'getPolicyPayDetails']);
Route::post('payment/qcbankpayment', [QuickPayController::class, 'qcbankpayment']);
Route::post('payment/dohabankpayment', [QuickPayController::class, 'dohabankpayment']);
Route::post('payment/tesspaymentspgw', [QuickPayController::class, 'tesspaymentspgw']);
Route::match(['get', 'post'], 'payment/paymentReturn', [QuickPayController::class, 'paymentReturn']);

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

Route::get('/vehicles/import', function () {
    return view('site.import');
})->name('vehicles.import.form');

Route::post('/vehicles/import', function (\Illuminate\Http\Request $request) {
        Excel::import(new VehiclesImport, $request->file('file'));

})->name('vehicles.import');

// Custom delete route for insurance records - shows confirmation page
Route::get('/admin/insurances/{id}/delete', function($id) {
    $insurance = \App\Models\Insurance::findOrFail($id);
    return view('filament.widgets.delete-confirmation', compact('insurance'));
})->name('admin.insurances.delete');

// Actual delete route
Route::post('/admin/insurances/{id}/confirm-delete', function($id) {
    Log::info('Delete route called for insurance ID: ' . $id);
    $insurance = \App\Models\Insurance::findOrFail($id);
    $insurance->delete();
    Log::info('Insurance deleted successfully');
    return redirect('/admin')->with('success', 'Insurance record deleted successfully');
})->name('admin.insurances.confirm-delete');
