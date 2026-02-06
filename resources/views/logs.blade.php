<!DOCTYPE html>
<html>
<head>
    <title>Activity Logs</title>

    <style>
        body {
            font-family: Arial, sans-serif;
        }

        table {
            border-collapse: collapse;
            width: 100%;
        }

        th, td {
            border: 1px solid black;
            padding: 8px;
            text-align: left;
        }

        th {
            background-color: #f2f2f2;
        }
    </style>
</head>
<body>

<!-- This page displays all activity logs recorded in the system -->

<h2>Activity Logs</h2>

<table>
    <thead>
        <tr>
            <th>ID</th>
            <th>Description</th>
            <th>Event</th>
            <th>Subject ID</th>
            <th>Date</th>
        </tr>
    </thead>

    <tbody>
        @forelse($logs as $log)
            <tr>
                <td>{{ $log->id }}</td>
                <td>{{ $log->description }}</td>
                <td>{{ $log->event }}</td>
                <td>{{ $log->subject_id }}</td>
                <td>{{ $log->created_at }}</td>
            </tr>
        @empty
            <tr>
                <td colspan="5">No Activity Logs Found</td>
            </tr>
        @endforelse
    </tbody>
</table>

</body>
</html>
