<!DOCTYPE html>
<html>
<head>
    <title>Policy Reports</title>
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
        }

        th, td {
            border: 1px solid black;
            padding: 8px;
            text-align: left;
        }
    </style>
</head>
<body>
<h1>Policy Reports</h1>
<table>
    <thead>
    <tr>
        <th>Date</th>
        <th>Ref</th>
        <th>Policy/Description</th>
        <th>Plate</th>
        <th>Amount</th>
        <th>Merchant TXN</th>
        <th>Bank TXN</th>
        <th>Response</th>
    </tr>
    </thead>
    <tbody>
    @foreach($data as $item)
        <tr>
            <td>{{ \Carbon\Carbon::parse($item->date)->format('d/m/Y') }}</td>
            <td>{{ $item->policy_ref }}</td>
            <td>{{ $item->quickPay->description ?? '' }}</td>
            <td>{{ $item->quickPay->ref_no ?? '' }}</td>
            <td>{{ $item->amount}}</td>
            <td>{{ $item->trans_key}}</td>
            <td>{{ $item->transaction_no}}</td>
            <td>{{ $item->status}}</td>
        </tr>
    @endforeach
    </tbody>
</table>
</body>
</html>