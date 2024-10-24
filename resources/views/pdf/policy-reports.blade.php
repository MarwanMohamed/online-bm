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
        <th>Ref #</th>
        <th>Type</th>
        <th>Insurance company</th>
        <th>Amount</th>
        <th>Agent</th>
        <th>Status</th>
        <th>Payment Status</th>
    </tr>
    </thead>
    <tbody>
    @foreach($data as $item)
        <tr>
            <td>{{ \Carbon\Carbon::parse($item->created_at)->format('d/m/Y') }}</td>
            <td>{{ $item->policy_id }}</td>
            <td>{{ $item->ins_type }}</td>
            <td>{{ $item->company->name ?? '' }}</td>
            <td>{{ $item->transaction->amount ?? '' }}</td>
            <td>{{ $item->user->name ?? '' }}</td>
            <td>{{ $item->getStatus->status ?? '' }}</td>
            <td>{{ $item->transaction->status ?? '' }}</td>
        </tr>
    @endforeach
    </tbody>
</table>
</body>
</html>