<?php

namespace App\Http\Controllers;

use App\Models\Insurance;
use App\Models\Quickpay;
use App\Models\Transaction;
use Illuminate\Http\Request;

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
                return $isExist;
//                $this->session->set_userdata('policyData', $qpayData);
//                echo json_encode($isExist);
            } elseif (isset($isExist->policy_id) && $isExist->status == 4) {
                $qpayData = array(
                    'total_amount' => $isExist->amount,
                    'policy_id' => $isExist->policy_id,
                    'quickpay' => false
                );
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
}