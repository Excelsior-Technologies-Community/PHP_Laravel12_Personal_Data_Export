<!DOCTYPE html>
<html>
<head>
    <title>Export History</title>

    <style>
        body{
            font-family: Arial;
            background:#f4f4f4;
            padding:40px;
        }

        table{
            width:100%;
            border-collapse:collapse;
            background:white;
        }

        th, td{
            padding:12px;
            border:1px solid #ddd;
            text-align:center;
        }

        th{
            background:#333;
            color:white;
        }
    </style>
</head>

<body>

<h2>Export History</h2>

<table>
    <tr>
        <th>User ID</th>
        <th>File Name</th>
        <th>Date</th>
    </tr>

    @foreach($exports as $exp)
    <tr>
        <td>{{ $exp->user_id }}</td>
        <td>{{ $exp->file_name }}</td>
        <td>{{ $exp->exported_at }}</td>
    </tr>
    @endforeach

</table>

</body>
</html>