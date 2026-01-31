<?php
use App\Enums\Feature;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\QuickPayController;
use App\Http\Controllers\RenewController;
use App\Http\Controllers\InsuranceController;
use App\Imports\VehiclesImport;
use App\Models\Insurance;
use App\Models\Quickpay;
use App\Models\Transaction;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Facades\Excel;
use TessPayments\Checkout\HashService;
use TessPayments\Core\Enums\Actions;

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

Route::get('test', function () {

    $request = request();
    $request->order_id = 'TR72086';
    $request->payment_id = '1';
    $transaction = Transaction::where('trans_key', $request->order_id)->first();
//    dd($transaction->policy_ref);
    $policyDesc = Quickpay::where('ref_no', $transaction->policy_ref)->where('deleted', 0)->value('description');
    if (!$policyDesc) {
        $policyDesc = Insurance::where('policy_id', $transaction->policy_ref)->where('deleted', 0)->value('description');
        $isQuickPay = false;
    }

    $description = !empty($policyDesc) ? $policyDesc : $transaction['policy_ref'];
    //Log::info("Transaction: " . print_r($transaction,true));
    //Log::info("Policy Desc: " . print_r($policyDesc,true));
    $params = [
        'id' => $request->payment_id,
        'order_number' => $request->order_id,
        'order_amount' => number_format($transaction->amount, 2, '.', ''),
        'order_currency' => 'QAR',
        'order_description' => $description
    ];

    $returnUrlHash = HashService::generate($params, Actions::RETURN_URL);
//    if($request->hash === $returnUrlHash && 'success' === $request->status) {
        Transaction::where('trans_key', $request->order_id)
            ->where('policy_ref', $transaction->policy_ref)
            ->update([
                'status' => 'Payment processed successfully',
                'transaction_no' => $request->payment_id
            ]);
        if ($isQuickPay) {
            Quickpay::where('ref_no', $transaction->policy_ref)
                ->update(['status' => 0]);
        } else {
            Insurance::where('policy_id', $transaction->policy_ref)
                ->update(['payment_status' => 1, 'status' => 2]);
        }
        $footerchk = 1;
        $data = [
            'order_id' => $request->order_id,
            'policy_ref' => $transaction->policy_ref,
            'order_info' => $description,
            'order_amount' => 'QAR ' . number_format($transaction->amount, 2),
            'order_status' => ('success' === $request->status ? 'Payment processed successfully.' : 'Payment unsuccessful'),
            'order_date' => date('d-m-Y', time())
        ];

        return view('site.payment.payment_confirm', compact('footerchk'))->with('data', $data);
//    }
});

Route::get('/', [HomeController::class, 'index']);

Route::get('/insurance/new', [InsuranceController::class, 'index']);
Route::get('/insurance/thirdparty', [InsuranceController::class, 'thirdparty']);
Route::get('/insurance/getVhlModels/{id}', [InsuranceController::class, 'getVhlModels']);
Route::get('/insurance/getVhlDetails/{make}', [InsuranceController::class, 'getVhlDetails']);
Route::get('/insurance/getPrice/{id}', [InsuranceController::class, 'getPrice']);
Route::get('/insurance/allowQid', [InsuranceController::class, 'allowQid']);
Route::post('/insurance/confirm', [InsuranceController::class, 'confirm']);


Route::get('/insurance/comprehensive', [InsuranceController::class, 'comprehensive']);
Route::post('/insurance/comprehensive', [InsuranceController::class, 'comprehensive']);


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
Route::get('payment/quickpay/receipt/{id}', [QuickPayController::class, 'showReceipt'])->name('quickpay.receipt');
Route::get('payment/quickpay/receipt/{id}/pdf', [QuickPayController::class, 'downloadReceiptPdf'])->name('quickpay.receipt.pdf');


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

// Site Kill Switch Routes - These routes work even when site is disabled
Route::get('/admin/kill-switch/disable', [\App\Http\Controllers\SiteKillSwitchController::class, 'disable'])
    ->name('admin.kill-switch.disable');
Route::get('/admin/kill-switch/enable', [\App\Http\Controllers\SiteKillSwitchController::class, 'enable'])
    ->name('admin.kill-switch.enable');
Route::get('/admin/kill-switch/status', [\App\Http\Controllers\SiteKillSwitchController::class, 'status'])
    ->name('admin.kill-switch.status');
