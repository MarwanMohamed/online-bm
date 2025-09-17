<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Confirm Delete</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f5f5f5;
            margin: 0;
            padding: 20px;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
        }
        .container {
            background: white;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            max-width: 500px;
            text-align: center;
        }
        .warning {
            color: #dc2626;
            font-size: 48px;
            margin-bottom: 20px;
        }
        .title {
            color: #dc2626;
            font-size: 24px;
            font-weight: bold;
            margin-bottom: 20px;
        }
        .details {
            background: #fef2f2;
            border: 1px solid #fecaca;
            border-radius: 6px;
            padding: 15px;
            margin: 20px 0;
            text-align: left;
        }
        .record-info {
            margin: 10px 0;
        }
        .buttons {
            margin-top: 30px;
        }
        .btn {
            padding: 12px 24px;
            margin: 0 10px;
            border: none;
            border-radius: 6px;
            font-size: 16px;
            cursor: pointer;
            text-decoration: none;
            display: inline-block;
        }
        .btn-danger {
            background-color: #dc2626;
            color: white;
        }
        .btn-danger:hover {
            background-color: #b91c1c;
        }
        .btn-secondary {
            background-color: #6b7280;
            color: white;
        }
        .btn-secondary:hover {
            background-color: #4b5563;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="warning">⚠️</div>
        <div class="title">Confirm Deletion</div>
        
        <p>You are about to permanently delete an insurance record. This action cannot be undone!</p>
        
        <div class="details">
            <div class="record-info"><strong>Record ID:</strong> {{ $insurance->id }}</div>
            <div class="record-info"><strong>Policy ID:</strong> {{ $insurance->policy_id ?? 'N/A' }}</div>
            <div class="record-info"><strong>Name:</strong> {{ $insurance->name ?? 'N/A' }}</div>
            <div class="record-info"><strong>Qatar ID:</strong> {{ $insurance->qid ?? 'N/A' }}</div>
        </div>
        
        <p><strong>Are you absolutely sure you want to delete this record?</strong></p>
        
        <div class="buttons">
            <form method="POST" action="/admin/insurances/{{ $insurance->id }}/confirm-delete" style="display: inline;">
                @csrf
                <button type="submit" class="btn btn-danger">Yes, Delete Permanently</button>
            </form>
            <a href="javascript:history.back()" class="btn btn-secondary">Cancel</a>
        </div>
    </div>
</body>
</html>
