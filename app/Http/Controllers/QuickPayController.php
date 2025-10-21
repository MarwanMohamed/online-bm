<?php

namespace App\Http\Controllers;

use App\Models\Insurance;
use App\Models\Quickpay;
use App\Models\Transaction;
use Illuminate\Http\Request;
use TessPayments\Checkout;
use TessPayments\Checkout\HashService;
use TessPayments\Core\Enums\Actions;
use Illuminate\Support\Facades\Log;

class QuickPayController extends Controller
{
    public function quickPay()
    {
        $footerchk = 1;
        return view('site.payment.quickpay')->with(['title' => 'Make Direct Payment', 'footerchk' => $footerchk]);
    }

    public function getPolicyPayDetails(Request $request)
    {
        $isExist = Insurance::where('policy_id', $request->policy_id)->where('deleted', 0)->first();
        if (!$isExist) {
            $isExist = Quickpay::where('ref_no', $request->policy_id)->where('deleted', 0)->first();

        }

        if (isset($isExist)) {
            if (isset($isExist->ref_no) && $isExist->status == 1) {
                $qpayData = array(
                    'total_amount' => $isExist->amount,
                    'policy_id' => $isExist->ref_no,
                    'quickpay' => true
                );
                $isExist->policy_type_display = ($isExist->category && $isExist->policy_type) ? "{$isExist->category}/{$isExist->policy_type}" : 'Insurance';
                return $isExist;
//                $this->session->set_userdata('policyData', $qpayData);
//                echo json_encode($isExist);
            } elseif (isset($isExist->policy_id) && $isExist->status == 4) {
                $qpayData = array(
                    'total_amount' => $isExist->amount,
                    'policy_id' => $isExist->policy_id,
                    'quickpay' => false
                );
                // Add policy type information for dynamic message
                $isExist->policy_type_display = 'Insurance'; // Default for regular insurance
                return $isExist;
//                $this->session->set_userdata('policyData', $qpayData);
//                echo json_encode($isExist);
            } else {
                echo 2;
            }
        } else {
            echo 0; //alreadyexist
        }
    }

    public function selectPayment(Request $request)
    {
        $total_amount = $request->nmi_total_price;
        $policyRef = $request->nmi_referno;
        $footerchk = 1;
        return view('site.payment.select-payment')->with(['title' => 'Select Payment', 'policyRef' => $policyRef, 'total_amount' => $total_amount, 'footerchk' => $footerchk]);
    }

    public function tesspaymentspgw(Request $request)
    {
        $refNo = $request->policy_id;
        $policyDetails = $this->getPolicyPayDetails($request);
        //App\Models\Quickpay Object ( [connection:protected] => mysql [table:protected] => quickpay [primaryKey:protected] => id 
        // [keyType:protected] => int [incrementing] => 1 [with:protected] => Array ( ) [withCount:protected] => Array ( )
        //  [preventsLazyLoading] => [perPage:protected] => 15 [exists] => 1 [wasRecentlyCreated] => 
        // [escapeWhenCastingToString:protected] => [attributes:protected] => Array ( [id] => 27201 [category] => [policy_type] => 
        // [ref_no] => MotTpl2548 [name] => JohnTest [email] => john@yahoo.com [amount] => 1 [description] => Motor TPL | PNo:21425 |2015 
        // [contact] => 24166587 [created_by] => 25 [created_at] => 2024-09-17 14:26:25 [updated_at] => 0000-00-00 00:00:00 [updated_by] => 
        // [status] => 1 [deleted] => 0 [policy_type_display] => Insurance ) [original:protected] => Array ( [id] => 27201 [category] => 
        // [policy_type] => [ref_no] => MotTpl2548 [name] => JohnTest [email] => john@yahoo.com [amount] => 1 [description] => Motor TPL | PNo:21425 
        // |2015 [contact] => 24166587 [created_by] => 25 [created_at] => 2024-09-17 14:26:25 [updated_at] => 0000-00-00 00:00:00 [updated_by] => [status] => 1 
        // [deleted] => 0 ) [changes:protected] => Array ( ) [casts:protected] => Array ( ) [classCastCache:protected] => Array ( ) 
        // [attributeCastCache:protected] => Array ( ) [dateFormat:protected] => [appends:protected] => Array ( ) [dispatchesEvents:protected] => Array ( ) 
        // [observables:protected] => Array ( ) [relations:protected] => Array ( ) [touches:protected] => Array ( ) [timestamps] => 1 [usesUniqueIds] => 
        // [hidden:protected] => Array ( ) [visible:protected] => Array ( ) [fillable:protected] => Array ( ) [guarded:protected] => 
        // Array ( [0] => id [1] => created_at [2] => updated_at ) )
        $checkout = new \TessPayments\Checkout\CheckoutService();
        //var_dump(url("/") . "/paymentReturn/TessReturn?status=success");
        $orderNumber = 'T-' . time() . '-' . $refNo;
        $tessResponse = $checkout->standardPayment([
            "operation" => "purchase",
            "order" => [
                "number" => $orderNumber,
                "amount" => number_format($policyDetails['amount'], 2),
                "currency" => "QAR",
                "description" => $policyDetails['description'],
            ],
            "cancel_url" => url("/") . "/payment/paymentReturn?status=cancelled",
            "success_url" => url("/") . "/payment/paymentReturn?status=success",
            "customer" => [
                "name" => $policyDetails['name'],
                "email" => $policyDetails['email']
            ]
        ]);
        if(!empty($tessResponse['redirect_url'])){
            if (isset($refNo)) {
                Transaction::create([
                    'policy_ref' => $refNo,
                    'trans_key' => $orderNumber,
                    'amount' => number_format($policyDetails['amount'], 2),
                    'status' => 'Pending',
                    'date' => date('Y-m-d H:i:s', time()),
                    'txn_type' => 'Other',
                    'payment_gateway' => 'TESS',
                    'active' => 1
                ]);
            }
            return redirect($tessResponse['redirect_url']);
        }
    }

    public function qcbankpayment(Request $request)
    {
        $qpay = \DB::table('pg_qpay_dtls')->first();
        $time = time();
        $amount = $request->nmi_total_price;
        $refNo = $request->nmi_referno;
        $sessId = bin2hex(random_bytes(32));
        $trXnId = $this->getUniqueTransactionKeyDebit();

        $data['Action'] = 0;
        $data['Amount'] = (int)($amount * 100);
        $data['BankID'] = $qpay->bank_id;
        $data['CurrencyCode'] = $qpay->cur_code;
        // $data['ExtraFields_f14'] = base_url().'paymentReturn/QpayReturn';
        $data['Lang'] = $qpay->lang;
        $data['MerchantID'] = $qpay->merchant_id;
        $data['MerchantModuleSessionID'] = $sessId;
        $data['NationalID'] = $qpay->national_id;
        $data['Pun'] = $trXnId;
        $data['PaymentDescription'] = $refNo;
        $data['Qantity'] = 1;
        $data['TransactionRequestDate'] = date('dmYHis', $time);
        $orderedString = $qpay->secure_hash;
        ksort($data);
        foreach ($data as $k => $param) {
            $orderedString .= $param;
        }
        $data['secureHash'] = hash('sha256', $orderedString, false);
        $data['QPUrl'] = $qpay->url;
        if (isset($refNo) && $trxType = 'debit') {
            Transaction::create([
                'policy_ref' => $refNo,
                'trans_key' => $trXnId,
                'amount' => $amount,
                'status' => 'Pending',
                'date' => date('Y-m-d H:i:s', $time),
                'txn_type' => 'Debit',
                'active' => 1
            ]);
        }
        return  redirect('https://qbima.qa/payment/qcbankpayment')->with('data', $data); // will delete it
        return view('site.payment.qcb_redirect')->with('data', $data);
    }


    public function dohabankpayment(Request $request)
    {
        $bkdtls = \DB::table('pg_dohabank_dtls')->where('ref_id', 'VPC001')->first();

        $vpc_amount = $request->nmi_total_price;
        $policyRef = $request->nmi_referno;
        $OrderInfo = time();
        $return_url = '/paymentReturn';
        $SECURE_SECRET = $bkdtls->secret_key;
        $transkey = $this->getUniqueTransactionKeyCredit();
        $data = [
            'policy_ref' => $policyRef,
            'trans_key' => $transkey,
            'amount' => $vpc_amount,
            'status' => 'Pending',
            'date' => date('Y-m-d H:i:s', $OrderInfo),
            'txn_type' => 'Credit',
            'active' => 1
        ];
        Transaction::create($data);

        $orginalAmount = $vpc_amount * 100;
        $vpcURL = "vpc_AccessCode=$bkdtls->access_code&vpc_Amount=$orginalAmount&vpc_Command=$bkdtls->command&vpc_Locale=$bkdtls->locale&vpc_MerchTxnRef=$transkey&vpc_Merchant=$bkdtls->merchant&vpc_OrderInfo=$OrderInfo&vpc_ReturnURL=$return_url&vpc_Version=$bkdtls->vpc_version";
        if (strlen($SECURE_SECRET) > 0) {
            $hexstr = pack('H*', $SECURE_SECRET);
            $vpcURL .= "&vpc_SecureHash=" . strtoupper(hash_hmac('sha256', $vpcURL, $hexstr));
            $vpcURL .= "&vpc_SecureHashType=sha256";
            $redirect_url = $bkdtls->url . "?" . $vpcURL;
            return  redirect('https://qbima.qa/payment/dohabankpayment')->with('data', $data); // will delete it
            return redirect($redirect_url);
        }
    }

    private function getUniqueTransactionKeyDebit()
    {
        do {
            $key = sprintf('TRD%05d%05d', rand(1, 99999), rand(1, 99999));
            $exitst = Transaction::where('trans_key', $key)->first();
        } while ($exitst);
        return $key;
    }

    private function getUniqueTransactionKeyCredit()
    {
        do {
            $key = sprintf('TRQ%05d', rand(1, 99999));
            $exitst = Transaction::where('trans_key', $key)->first();
        } while ($exitst);
        return $key;
    }

    private function getCategoryDisplayName($category)
    {
        $categoryMap = [
            'general' => 'General',
            'medical' => 'Medical',
            'mvhi' => 'MVHI',
            'life' => 'Life',
            'motor' => 'Motor',
        ];

        return $categoryMap[$category] ?? 'General';
    }

    public function paymentReturn(Request $request)
    {
        Log::info('Payment Return Data:', $request->all());

        $transaction = Transaction::where('trans_key', $request->order_id)->first();
        $policyDesc = Quickpay::where('ref_no', $transaction->policy_ref)->where('deleted', 0)->value('description');
        if (!$policyDesc) {
            $policyDesc = Insurance::where('policy_id', $transaction->policy_ref)->where('deleted', 0)->value('description');
        }
        Log::info($policyDesc);
        $params = [
            'id' => $request->payment_id,
            'order_number' => $request->order_id,
            'order_amount' => number_format($transaction->amount, 2),
            'order_currency' => 'QAR',
            'order_description' => $policyDesc
        ];
        $returnUrlHash = HashService::generate($params, Actions::RETURN_URL);
        if($request->hash === $returnUrlHash && 'success' === $request->status)
        {
            Transaction::where('trans_key', $request->order_id)
                ->where('policy_ref', $transaction->policy_ref)
                ->update([
                    'status' => 'Payment processed successfully',
                    'transaction_no' => $request->payment_id
                ]);
            Quickpay::where('ref_no', $transaction->policy_ref)
                ->update(['status' => 0]);

            return response('Payment processed successfully', 200);
        }
        else
        {
            return response('Payment could not be processed', 200);
        }
    }
}