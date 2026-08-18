<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Hasil Screening</title>
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            background-color: #f9f9f9;
            padding: 20px;
            color: #333;
            font-size: 12px;
        }

        .container {
            width: 70%;
            margin: 0 auto;
            margin: 0 auto;
            background: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);
        }

        .header {
            text-align: center;
            margin-bottom: 20px;
        }

        .header h1 {
            font-size: 18px;
        }

        .info {
            margin-bottom: 15px;
        }

        .info p {
            margin: 4px 0;
        }

        .table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
            font-size: 12px;
        }

        .table th,
        .table td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: center;
        }

        .table th {
            background-color: #4A5568;
            color: white;
        }

        .table tr:nth-child(even) {
            background-color: #f2f2f2;
        }

        .table tr:hover {
            background-color: #e2e8f0;
        }

        .status-box {
            color: white;
            padding: 10px;
            text-align: center;
            border-radius: 8px;
            margin-bottom: 15px;
            font-size: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .icon {
            width: 20px;
            height: 20px;
            margin-right: 8px;
        }

        .green {
            background-color: #38a169;
        }

        .yellow {
            background-color: #ecc94b;
            color: black;
        }

        .red {
            background-color: #e53e3e;
        }

        .badge {
            display: inline-block;
            padding: 4px 8px;
            border-radius: 10px;
            font-weight: bold;
            font-size: 12px;
            color: white;
        }

        .badge.ket {
            display: inline-block;
            padding: 4px 8px;
            border-radius: 10px;
            font-weight: bold;
            font-size: 14px;
            color: black;
        }

        .badge.green {
            background-color: #38a169;
        }

        .badge.yellow {
            background-color: #ecc94b;
            color: black;
        }

        .badge.red {
            background-color: #e53e3e;
        }

        /* Style untuk tombol kembali */
        .back-button {
            display: inline-block;
            padding: 8px 16px;
            background-color: #38a169;
            color: white;
            text-decoration: none;
            border-radius: 4px;
            font-weight: bold;
            margin-bottom: 20px;
            transition: background-color 0.3s;
        }

        .back-button:hover {
            background-color: #52aa7b;
        }

        .back-button i {
            margin-right: 8px;
        }
    </style>
</head>

<body>

    <div class="container">
        <div class="header">
            <h1>Hasil Screening Kesehatan</h1>
        </div>

        <div class="info">
            <h3>Data Pasien:</h3>
            <p><strong>Nama:</strong> {{ $user->name ?? '-' }}</p>
            <p><strong>NIK:</strong> {{ $user->nik ?? '-' }}</p>
            <p><strong>Email:</strong> {{ $user->email ?? '-' }}</p>
            <p><strong>No HP:</strong> {{ $user->nohp ?? '-' }}</p>
            <!-- Foto Profil -->


        </div>

        {{-- Status Risiko --}}
        @php
        $colorMap = [
        'Risiko Rendah' => 'green',
        'Risiko Sedang' => 'yellow',
        'Risiko Tinggi' => 'red',
        ];
        $iconMap = [
        'Risiko Rendah' => '✅',
        'Risiko Sedang' => '⚠️',
        'Risiko Tinggi' => '❗',
        ];
        $color = $colorMap[$risikoMayoritas] ?? 'gray';
        $icon = $iconMap[$risikoMayoritas] ?? '';
        $jumlahRisiko = $riskCount[$risikoMayoritas] ?? 0;
        @endphp

        <div class="status-box {{ $color }}">
            <h2>
                Status Risiko: {{ $icon }} <strong>{{ $risikoMayoritas }}</strong>
                ({{ $jumlahRisiko }} {{ $risikoMayoritas }})
            </h2>
        </div>

        <h3>Total Skor: {{ $result->score_total }}</h3>

        <h3>Rangkuman Skor per Kategori:</h3>
        <table class="table">
            <thead>
                <tr>
                    <th>Kategori</th>
                    <th>Skor</th>
                    <th>Status</th>
                    <th>Keterangan</th>
                </tr>
            </thead>
            <tbody>
                @foreach($categories as $category)
                @php
                $summary = $categorySummary[$category->id] ?? null;
                @endphp
                @if($summary)
                <tr>
                    <td>{{ $summary['name'] }}</td>
                    <td>{{ $summary['skor'] }}</td>
                    <td>
                        @php
                        $status = $categorySummary[$category->id]['status_risiko'] ?? 'Tidak Diketahui';
                        $badgeColor = match(strtolower(trim($status))) {
                        'risiko rendah', 'rendah' => 'green',
                        'risiko sedang', 'sedang' => 'yellow',
                        'risiko tinggi', 'tinggi' => 'red',
                        default => 'gray',
                        };
                        @endphp

                        <span class="badge {{ $badgeColor }}">
                            {{ $status }}
                        </span>
                    </td>
                    <td>
                        <span class="badge-ket">{{ $summary['jumlah_benar'] }} dari {{ $summary['jumlah_soal'] }}
                            Pertanyaan</span>
                    </td>
                </tr>
                @endif
                @endforeach
            </tbody>
        </table>

        <!-- Tambahkan tombol di bagian atas halaman -->
        <div style="margin: 20px 0;">
            <a href="/reports" class="back-button">
                <i class="fas fa-arrow-left"></i> Kembali ke laporan
            </a>
        </div>

        <p><strong>Tanggal Cetak:</strong> {{ \Carbon\Carbon::now()->format('d-m-Y H:i') }}</p>
    </div>

</body>

</html>