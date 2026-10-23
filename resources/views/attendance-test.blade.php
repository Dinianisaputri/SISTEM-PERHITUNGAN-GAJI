<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Attendance Test</title>

    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 30px;
        }

        h1 {
            margin-bottom: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th,
        td {
            border: 1px solid #ddd;
            padding: 10px;
            text-align: left;
        }

        th {
            background: #f5f5f5;
        }
    </style>
</head>

<body>

    <h1>Data Fingerprint</h1>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Device SN</th>
                <th>PIN</th>
                <th>Tanggal & Jam</th>
                <th>Status</th>
                <th>Verify</th>
                <th>Work Code</th>
                <th>Source IP</th>
                <th>Diterima</th>
            </tr>
        </thead>

        <tbody>
            @forelse ($logs as $log)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $log->device_sn }}</td>
                    <td>{{ $log->pin }}</td>
                    <td>{{ $log->punched_at }}</td>
                    <td>{{ $log->status }}</td>
                    <td>{{ $log->verify }}</td>
                    <td>{{ $log->work_code }}</td>
                    <td>{{ $log->source_ip }}</td>
                    <td>{{ $log->received_at }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="9">
                        Belum ada data fingerprint.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>

</body>
</html>