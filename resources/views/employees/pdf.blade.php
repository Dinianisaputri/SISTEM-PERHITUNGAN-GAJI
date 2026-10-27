
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Data Karyawan</title>
    
<style>
    @page {
        margin: 18px;
    }

    body {
        font-family: DejaVu Sans, sans-serif;
        font-size: 9px;
        color: #222;
    }

    h2 {
        text-align: center;
        margin-bottom: 5px;
    }

    .info {
        margin-bottom: 12px;
        line-height: 1.6;
    }

    table {
        width: 100%;
        border-collapse: collapse;
        table-layout: fixed;
    }

    th, td {
        border: 1px solid #777;
        padding: 5px;
        vertical-align: top;
        overflow-wrap: break-word;
        word-wrap: break-word;
    }

    th {
        background: #e9eef5;
        text-align: center;
    }

    tr {
        page-break-inside: avoid;
    }

    th:nth-child(1), td:nth-child(1) {
        width: 5%;
        text-align: center;
    }

    th:nth-child(2), td:nth-child(2) {
        width: 19%;
    }

    th:nth-child(3), td:nth-child(3) {
        width: 12%;
    }

    th:nth-child(4), td:nth-child(4) {
        width: 16%;
    }

    th:nth-child(5), td:nth-child(5) {
        width: 48%;
    }
</style>
</head>
<body>
    <h2>DAFTAR DATA KARYAWAN</h2>

    <div class="info">
        Tanggal cetak:
        {{ $printedAt->format('d-m-Y H:i') }}<br>

        Jumlah karyawan:
        {{ $employees->count() }} orang
    </div>

    <table>
        <thead>
    <tr>
        <th>No.</th>
        <th>Nama</th>
        <th>Bagian</th>
        <th>Jabatan</th>
        <th>Alamat</th>
    </tr>
</thead>
<tbody>
    @forelse ($employees as $employee)
        <tr>
            <td>{{ $loop->iteration }}</td>
            <td>{{ $employee->name ?? '-' }}</td>
            <td>{{ $employee->bagian ?? '-' }}</td>
            <td>{{ $employee->jabatan ?? '-' }}</td>
            <td>{{ $employee->alamat_lengkap ?? '-' }}</td>
        </tr>
    @empty
        <tr>
            <td colspan="5" style="text-align: center;">
                Tidak ada data karyawan yang sesuai dengan filter.
            </td>
        </tr>
    @endforelse
</tbody>
    </table>
</body>
</html>
