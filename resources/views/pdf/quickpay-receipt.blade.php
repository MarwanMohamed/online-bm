<!DOCTYPE html>
<html>
<head>
    <title>E-Receipt - Quick Pay</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12pt;
            color: #08185A;
            background-color: #FFFFFF;
            margin: 20px;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #08185A;
            padding-bottom: 20px;
        }
        .header h1 {
            color: #08185A;
            font-size: 24pt;
            margin: 0;
        }
        .header h2 {
            color: #08185A;
            font-size: 18pt;
            margin: 10px 0 0 0;
        }
        .receipt-info {
            margin: 20px 0;
        }
        .receipt-info table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        .receipt-info th {
            background-color: #E1E1E1;
            padding: 10px;
            text-align: left;
            font-weight: bold;
            border: 1px solid #08185A;
        }
        .receipt-info td {
            padding: 10px;
            border: 1px solid #08185A;
        }
        .receipt-info tr.shade {
            background-color: #F5F5F5;
        }
        .amount-section {
            margin-top: 30px;
            text-align: right;
            font-size: 14pt;
            font-weight: bold;
        }
        .footer {
            margin-top: 50px;
            text-align: center;
            font-size: 10pt;
            color: #666;
            border-top: 1px solid #08185A;
            padding-top: 20px;
        }
        .status-badge {
            padding: 5px 15px;
            background-color: #00AA00;
            color: white;
            border-radius: 5px;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>E-RECEIPT</h1>
        <h2>Quick Pay Payment Receipt</h2>
    </div>

    <div class="receipt-info">
        <table>
            <tr class="title">
                <th colspan="2">Payment Details</th>
            </tr>
            <tr>
                <td width="40%"><strong>Reference Number:</strong></td>
                <td>{{ $quickpay->ref_no }}</td>
            </tr>
            <tr class="shade">
                <td><strong>Policy Group:</strong></td>
                <td>{{ $quickpay->category ?? 'N/A' }}</td>
            </tr>
            <tr>
                <td><strong>Policy Type:</strong></td>
                <td>{{ $quickpay->policy_type ?? 'N/A' }}</td>
            </tr>
            <tr class="shade">
                <td><strong>Customer Name:</strong></td>
                <td>{{ $quickpay->name }}</td>
            </tr>
            <tr>
                <td><strong>Email:</strong></td>
                <td>{{ $quickpay->email }}</td>
            </tr>
            <tr class="shade">
                <td><strong>Contact:</strong></td>
                <td>{{ $quickpay->contact }}</td>
            </tr>
            <tr>
                <td><strong>Description:</strong></td>
                <td>{{ $quickpay->description }}</td>
            </tr>
            @if($transaction)
            <tr class="shade">
                <td><strong>Transaction Key:</strong></td>
                <td>{{ $transaction->trans_key }}</td>
            </tr>
            <tr>
                <td><strong>Transaction Number:</strong></td>
                <td>{{ $transaction->transaction_no ?? 'N/A' }}</td>
            </tr>
            <tr class="shade">
                <td><strong>Transaction Date:</strong></td>
                <td>{{ \Carbon\Carbon::parse($transaction->date ?? $quickpay->created_at)->format('d/m/Y h:i A') }}</td>
            </tr>
            @endif
            <tr>
                <td><strong>Payment Status:</strong></td>
                <td>
                    <span class="status-badge">{{ $quickpay->status == 0 ? 'Paid' : 'Unpaid' }}</span>
                </td>
            </tr>
            <tr class="shade">
                <td><strong>Created Date:</strong></td>
                <td>{{ \Carbon\Carbon::parse($quickpay->created_at)->format('d/m/Y h:i A') }}</td>
            </tr>
            @if($quickpay->user)
            <tr>
                <td><strong>Agent:</strong></td>
                <td>{{ $quickpay->user->name }}</td>
            </tr>
            @endif
        </table>
    </div>

    <div class="amount-section">
        <table style="width: 50%; margin-left: auto;">
            <tr>
                <td style="text-align: right; padding: 10px; border: 1px solid #08185A;"><strong>Total Amount:</strong></td>
                <td style="text-align: right; padding: 10px; border: 1px solid #08185A; background-color: #E1E1E1;"><strong>QAR {{ number_format($quickpay->amount, 2) }}</strong></td>
            </tr>
        </table>
    </div>

    <div class="footer">
        <p>This is an electronically generated receipt. No signature is required.</p>
        <p>Generated on: {{ \Carbon\Carbon::now()->format('d/m/Y h:i A') }}</p>
    </div>
</body>
</html>

