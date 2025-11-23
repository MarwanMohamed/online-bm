<!DOCTYPE html>
<html>
<head>
    <title>Payment Confirmation - Quick Pay</title>
    <meta charset="utf-8">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: Arial, Verdana, sans-serif;
            font-size: 10pt;
            color: #08185A;
            background-color: #FFFFFF;
            line-height: 1.6;
        }
        .header-section {
            padding: 20px 40px;
            border-bottom: 2px solid #E1E1E1;
            margin-bottom: 20px;
        }
        .header-content {
            display: table;
            width: 100%;
        }
        .logo-container {
            display: table-cell;
            vertical-align: middle;
            width: 120px;
            padding-right: 20px;
        }
        .logo-container img {
            max-width: 100px;
            max-height: 80px;
            height: auto;
        }
        .company-info {
            display: table-cell;
            vertical-align: middle;
        }
        .company-name {
            font-size: 18pt;
            color: #08185A;
            font-weight: bold;
            margin-bottom: 5px;
        }
        .company-tagline {
            font-size: 10pt;
            color: #666;
        }
        .breadcrumb {
            padding: 10px 40px;
            background-color: #F5F5F5;
            border-bottom: 1px solid #E1E1E1;
            margin-bottom: 30px;
        }
        .breadcrumb-item {
            color: #08185A;
            text-decoration: none;
            font-size: 9pt;
        }
        .breadcrumb-item.active {
            color: #666;
        }
        .container {
            max-width: 900px;
            margin: 0 auto;
            padding: 0 40px;
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
        }
        .payment-details td:last-child {
            width: 60%;
            text-align: left;
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
        .footer-links {
            margin: 40px 0 20px 0;
            text-align: center;
            padding: 20px 0;
            border-top: 1px solid #E1E1E1;
        }
        .footer-links a {
            color: #08185A;
            text-decoration: none;
            margin: 0 15px;
            font-size: 9pt;
            font-weight: bold;
        }
        .general-insurance {
            text-align: center;
            margin: 20px 0;
            font-size: 10pt;
            color: #08185A;
            font-weight: bold;
        }
        .copyright {
            background-color: #b50555;
            color: #FFFFFF;
            text-align: center;
            padding: 15px 0;
            font-size: 9pt;
        }
        .copyright a {
            color: #FFFFFF;
            font-weight: 600;
        }
    </style>
</head>
<body>
    <!-- Header Section -->
    <div class="header-section">
        <div class="header-content">
            <div class="logo-container">
                @php
                    $logoPath = public_path('assets/img/logo.png');
                @endphp
                @if(file_exists($logoPath))
                <img src="file://{{ str_replace('\\', '/', $logoPath) }}" alt="Qatar Bima Logo" style="max-width: 100px; max-height: 80px;">
                @endif
            </div>
            <div class="company-info">
                <div class="company-name">Qatar Bima International W.L.L</div>
                <div class="company-tagline">Insurance Brokers</div>
            </div>
        </div>
    </div>

    <!-- Breadcrumb Navigation -->
    <div class="breadcrumb">
        <span class="breadcrumb-item">Home</span>
        <span style="color: #666; margin: 0 5px;">></span>
        <span class="breadcrumb-item active">Payment Confirm</span>
    </div>

    <!-- Main Content -->
    <div class="container">
        <div class="payment-details">
            <table>
                <tr>
                    <td>Policy Reference</td>
                    <td>{{ $quickpay->ref_no }}</td>
                </tr>
                <tr>
                    <td>Description</td>
                    <td>{{ $quickpay->description ?? ($quickpay->category ?? 'N/A') }}</td>
                </tr>
                <tr>
                    <td>Amount</td>
                    <td><strong>QAR {{ number_format($quickpay->amount, 2) }}</strong></td>
                </tr>
                <tr>
                    <td>Status</td>
                    <td class="status-success">{{ $quickpay->status == 0 ? 'Success' : 'Unpaid' }}</td>
                </tr>
                <tr>
                    <td>Date</td>
                    <td>{{ \Carbon\Carbon::parse($transaction->date ?? $quickpay->created_at)->format('d-m-Y') }}</td>
                </tr>
                @if($quickpay->name)
                <tr>
                    <td>Customer Name</td>
                    <td>{{ $quickpay->name }}</td>
                </tr>
                @endif
                @if($quickpay->email)
                <tr>
                    <td>Email</td>
                    <td>{{ $quickpay->email }}</td>
                </tr>
                @endif
                @if($quickpay->contact)
                <tr>
                    <td>Contact</td>
                    <td>{{ $quickpay->contact }}</td>
                </tr>
                @endif
                @if($transaction && $transaction->trans_key)
                <tr>
                    <td>Transaction Key</td>
                    <td>{{ $transaction->trans_key }}</td>
                </tr>
                @endif
                @if($transaction && $transaction->transaction_no)
                <tr>
                    <td>Transaction Number</td>
                    <td>{{ $transaction->transaction_no }}</td>
                </tr>
                @endif
            </table>
        </div>
    </div>

    <!-- Footer Links -->
    <div class="footer-links">
        <a href="#">Terms & Condition</a>
        <a href="#">Privacy policy</a>
        <a href="#">About us</a>
    </div>

    <!-- General Insurance Section -->
    <div class="general-insurance">
        General Insurance
    </div>

    <!-- Copyright Footer -->
    <div class="copyright">
        © {{ date('Y') }} Qatar Bima International W.L.L - Design and Development by <a href="http://bluelynx.qa" target="_blank">Blue Lynx</a>
    </div>
</body>
</html>
