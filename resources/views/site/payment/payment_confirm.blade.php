@extends('site.layout')

@section('content')
    <style>
        .receipt-container {
            max-width: 900px;
            margin: 30px auto;
            background-color: #FFFFFF;
            padding: 20px;
        }
        .payment-details {
            margin: 30px 0;
        }
        .payment-details table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
        }
        .payment-details td {
            padding: 12px 15px;
            border: 1px solid #E1E1E1;
            font-size: 10pt;
        }
        .payment-details td:first-child {
            width: 40%;
            text-align: left;
            font-weight: bold;
            background-color: #F5F5F5;
        }
        .payment-details td:last-child {
            width: 60%;
            text-align: left;
            background-color: #FFFFFF;
        }
        .payment-details tr:nth-child(even) td:first-child {
            background-color: #F5F5F5;
        }
        .payment-details tr:nth-child(even) td:last-child {
            background-color: #F5F5F5;
        }
        .payment-details tr:nth-child(odd) td:first-child {
            background-color: #FFFFFF;
        }
        .payment-details tr:nth-child(odd) td:last-child {
            background-color: #FFFFFF;
        }
        .status-success {
            color: #00AA00;
            font-weight: bold;
        }
        .action-buttons {
            margin: 30px 0;
            text-align: center;
        }
        .btn {
            display: inline-block;
            padding: 12px 30px;
            margin: 0 10px;
            background-color: #b50555;
            color: #FFFFFF;
            text-decoration: none;
            border: none;
            border-radius: 4px;
            font-size: 11pt;
            font-weight: bold;
            cursor: pointer;
        }
        .btn:hover {
            background-color: #8d0442;
        }
        .btn-print {
            background-color: #b50555;
        }
        .btn-pdf {
            background-color: #b50555;
        }
        .btn-order {
            background-color: #b50555;
            padding: 12px 40px;
        }
        @media print {
            .action-buttons {
                display: none;
            }
            .breadcrumb {
                display: none;
            }
        }
    </style>

    <div class="breadcrumb">
        <div class="container">
            <div class="row">
                <nav>
                    <a class="breadcrumb-item" href="/"><i class="fa fa-home"></i> Home&nbsp;<i class="fa fa-angle-right"></i></a>
                    <span class="breadcrumb-item active">Payment Receipt</span>
                </nav>
            </div>
        </div>
    </div>

    <div id="free-promo">
        <div class="container">
            <div class="receipt-container">
                <div class="payment-details">
                    <table>
                        <tr>
                            <td>Policy Reference</td>
                            <td>{{ $data['policy_ref'] }}</td>
                        </tr>
                        <tr>
                            <td>Description</td>
                            <td>{{ $data['order_info'] ?? 'N/A' }}</td>
                        </tr>
                        <tr>
                            <td>Amount</td>
                            <td><strong>{{ $data['order_amount'] }}</strong></td>
                        </tr>
                        <tr>
                            <td>Status</td>
                            <td class="status-success">{{ $data['order_status'] }}</td>
                        </tr>
                        <tr>
                            <td>Date</td>
                            <td>{{ \Carbon\Carbon::parse($data['order_date'])->format('d-m-Y') }}</td>
                        </tr>
                    </table>
                </div>

                <!-- Action Buttons -->
                <div class="action-buttons">
                    <button class="btn btn-print" onclick="window.print();">Print Receipt</button>
                    <a href="{{ route('quickpay.receipt.pdf', $data['order_id']) }}" class="btn btn-pdf" target="_blank">Download PDF</a>
                    <a href="/payment/quickpay" class="btn btn-order">Order Again</a>
                </div>
            </div>
        </div>
    </div>
@stop

@section('script')
    <script type="text/javascript">
        //disable back button browser
        history.pushState(null, null, location.href);
        window.onpopstate = function () {
            history.go(1);
        };
    </script>
@stop

