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
        <th>Id</th>
        <th>Date</th>
        <th>Reference</th>
        <th>Name</th>
        <th>Qatar ID</th>
        <th>Policy Status</th>
        <th>Commit By</th>
    </tr>
    </thead>
    <tbody>
    @foreach($data as $item)
        <tr>
            <td>{{ $item->id }}</td>
            <td>{{ \Carbon\Carbon::parse($item->start_date)->format('d/m/Y') }}</td>
            <td>{{ $item->policy_id }}</td>
            <td>{{ $item->name }}</td>
            <td>{{ $item->qid}}</td>
            <td>{{ $item->getStatus->status ?? ''}}</td>
            <td>{{ $item->user->name ?? ''}}</td>
        </tr>
    @endforeach
    </tbody>
</table>
</body>
</html>