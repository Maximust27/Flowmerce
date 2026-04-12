<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>{{ $title }}</title>
    <style>
        body { font-family: sans-serif; font-size: 12px; }
        h1 { text-align: center; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
        .type-in { color: green; font-weight: bold; }
        .type-out { color: red; font-weight: bold; }
    </style>
</head>
<body>
    <h1>{{ $title }}</h1>
    <p>Tanggal Cetak: {{ now()->format('d/m/Y H:i') }}</p>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Tanggal</th>
                <th>Produk</th>
                <th>Tipe</th>
                <th>Jumlah</th>
                <th>Keterangan</th>
            </tr>
        </thead>
        <tbody>
            @foreach($logs as $index => $log)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $log->created_at->format('d/m/Y H:i') }}</td>
                <td>{{ $log->product->name ?? '-' }}</td>
                <td>
                    <span class="{{ $log->type == 'IN' ? 'type-in' : 'type-out' }}">
                        {{ $log->type == 'IN' ? 'MASUK' : 'KELUAR' }}
                    </span>
                </td>
                <td>{{ $log->quantity }}</td>
                <td>{{ $log->reason }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
