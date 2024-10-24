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
        <th>Name</th>
        <th>Amount</th>
        <th>Agent</th>
        <th>Status</th>
    </tr>
    </thead>
    <tbody>
    @foreach($data as $item)
        <tr>
            <td>{{ \Carbon\Carbon::parse($item->created_at)->format('d/m/Y') }}</td>
            <td>{{ $item->ref_no }}</td>
            <td>{{ $item->name }}</td>
            <td>{{ $item->amount}}</td>
            <td>{{ $item->user->name ?? ''}}</td>
            <td>{{ $item->status == 0 ? 'Paid' : 'Unpaid'}}</td>
        </tr>
    @endforeach
    </tbody>
</table>
</body>
</html>