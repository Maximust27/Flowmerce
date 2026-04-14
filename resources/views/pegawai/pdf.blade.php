<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title>{{ $title }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 14px;
            color: #333;
        }
        h2 {
            text-align: center;
            margin-bottom: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        table, th, td {
            border: 1px solid #ddd;
        }
        th, td {
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
        .text-right {
            text-align: right;
        }
        .text-center {
            text-align: center;
        }
        .footer {
            font-size: 12px;
            text-align: center;
            color: #888;
            margin-top: 30px;
        }
    </style>
</head>
<body>

    <h2>{{ $title }}</h2>
    <p>Tanggal Cetak: {{ \Carbon\Carbon::now()->format('d M Y H:i') }}</p>

    <table>
        <thead>
            <tr>
                <th width="5%">No</th>
                <th width="30%">Nama Pegawai</th>
                <th width="35%">Email</th>
                <th width="15%">Role</th>
                <th width="15%">Bergabung Sejak</th>
            </tr>
        </thead>
        <tbody>
            @foreach($employees as $index => $employee)
            <tr>
                <td class="text-center">{{ $index + 1 }}</td>
                <td>{{ $employee->name }}</td>
                <td>{{ $employee->email }}</td>
                <td>{{ strtoupper($employee->role) }}</td>
                <td>{{ $employee->created_at->format('d M Y') }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        Diproduksi oleh Flowmerce &copy; {{ date('Y') }}
    </div>

</body>
</html>
