<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Skrining - {{ $user->name }}</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            font-size: 12px;
        }

        .header {
            text-align: center;
            margin-bottom: 20px;
        }

        .header h1 {
            font-size: 18px;
            margin-bottom: 5px;
        }

        .header p {
            font-size: 14px;
            margin: 0;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        th,
        td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }

        th {
            background-color: #f2f2f2;
            font-weight: bold;
        }

        .info-box {
            margin-bottom: 20px;
            padding: 10px;
            border: 1px solid #eee;
            background-color: #f9f9f9;
        }

        .footer {
            margin-top: 30px;
            text-align: right;
            font-size: 10px;
            color: #666;
        }
    </style>
</head>

<body>
    <div class="header">
        <h1>LAPORAN HASIL SKRINING</h1>
        <p>BAGIAN KESEHATAN</p>
    </div>

    <div class="info-box">
        <h3>Informasi Pengguna</h3>
        <table>
            <tr>
                <td width="20%"><strong>Nama</strong></td>
                <td width="30%">{{ $user->name }}</td>
                <td width="20%"><strong>Tanggal Cetak</strong></td>
                <td width="30%">{{ now()->format('d/m/Y H:i') }}</td>
            </tr>
            <tr>
                <td><strong>Email</strong></td>
                <td>{{ $user->email }}</td>
                <td><strong>Total Data</strong></td>
                <td>{{ count($results) }} hasil skrining</td>
            </tr>
        </table>
    </div>

    <h3>Data Hasil Skrining</h3>
    <table>
        <thead>
            <tr>
                <th width="20%">Tanggal</th>
                <th width="15%">Skor Total</th>
                <th width="25%">Status Risiko</th>
                <th width="20%">Sesi</th>
                <th width="20%">Keterangan</th>
            </tr>
        </thead>
        <tbody>
            @forelse($results as $item)
            <tr>
                <td>{{ $item->created_at->format('d/m/Y H:i') }}</td>
                <td>{{ $item->score_total }}</td>
                <td>{{ $item->risk_status }}</td>
                <td>{{ $item->screening_session }}</td>
                <td>
                    @if($item->score_total >= 70)
                    Risiko Tinggi
                    @elseif($item->score_total >= 40)
                    Risiko Sedang
                    @else
                    Risiko Rendah
                    @endif
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="5" style="text-align: center;">Tidak ada data skrining</td>
            </tr>
            @endforelse
        </tbody>
    </table>

    <div class="footer">
        Dicetak pada: {{ now()->format('d F Y H:i:s') }}
    </div>
</body>

</html>