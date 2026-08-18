<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Laporan Skrining</title>
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 12px;
            color: #333;
        }

        h3 {
            text-align: center;
            margin-bottom: 15px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
        }

        table,
        th,
        td {
            border: 1px solid #444;
        }

        th,
        td {
            padding: 6px;
            text-align: center;
        }

        th {
            background-color: #f2f2f2;
        }

        tr:nth-child(even) {
            background-color: #fafafa;
        }
    </style>
</head>

<body>

    <h3>Laporan Skrining - {{ ucfirst($filter) }}</h3>

    <table>
        <thead>
            <tr>
                <th>Nama</th>
                <th>Email</th>
                <th>Skor Total</th>
                <th>Status Risiko</th>
                <th>Tanggal</th>

            </tr>
        </thead>
        <tbody>
            @foreach ($results as $item)
            <tr>
                <td>{{ $item->user->name ?? 'N/A' }}</td>
                <td>{{ $item->screening_session }}</td>
                <td>{{ $item->score_total }}</td>
                <td>{{ $item->risk_status }}</td>
                <td>{{ $item->created_at->format('d-m-Y H:i') }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

</body>

</html>