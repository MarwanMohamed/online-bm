<!DOCTYPE html>
<html>
<head>
    <title>Customers Reports</title>
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
<h1>Customers Reports</h1>
<table>
    <thead>
    <tr>
        <th>Name</th>
        <th>QID</th>
        <th>Email</th>
        <th>Mobile</th>
        <th>Area</th>
    </tr>
    </thead>
    <tbody>
    @foreach($data as $item)
        <tr>
            <td>{{ $item->name }}</td>
            <td>{{ $item->qid }}</td>
            <td>{{ $item->email }}</td>
            <td>{{ $item->mobile }}</td>
            <td>{{ $item->getArea->area ?? '' }}</td>
        </tr>
    @endforeach
    </tbody>
</table>
</body>
</html>