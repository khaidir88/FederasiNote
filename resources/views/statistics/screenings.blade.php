<x-app-layout>
    <div class="py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="mb-8">
                <h1 class="text-3xl font-bold text-gray-900">Statistik Screening</h1>
                <p class="mt-2 text-sm text-gray-600">Data hasil screening kesehatan</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-10">
                <div class="bg-white p-6 rounded-lg shadow">
                    <h3 class="text-lg font-medium text-gray-500">Total Screening</h3>
                    <p class="text-3xl font-bold text-blue-600">{{ number_format($totalScreenings) }}</p>
                </div>
                <div class="bg-white p-6 rounded-lg shadow">
                    <h3 class="text-lg font-medium text-gray-500">Screening Hari Ini</h3>
                    <p class="text-3xl font-bold text-green-600">{{ number_format($todayScreenings) }}</p>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-10">
                <div class="bg-white p-6 rounded-lg shadow">
                    <h2 class="text-xl font-semibold text-gray-800 mb-4">Aktivitas Screening Harian</h2>
                    <div class="h-80">
                        <canvas id="dailyScreeningsChart"></canvas>
                    </div>
                </div>
                <div class="bg-white p-6 rounded-lg shadow">
                    <h2 class="text-xl font-semibold text-gray-800 mb-4">Distribusi Hasil Screening</h2>
                    <div class="h-80">
                        <canvas id="resultsDistributionChart"></canvas>
                    </div>
                </div>
            </div>

            <div class="bg-white overflow-hidden shadow rounded-lg">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h2 class="text-xl font-semibold text-gray-800">Detail Screening 30 Hari Terakhir</h2>
                </div>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Tanggal</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Jumlah</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Pertumbuhan</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($screenings as $screening)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap">{{ $screening->display_date }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">{{ $screening->total }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if(!$loop->last)
                                    @php
                                    $prev = $screenings[$loop->index + 1]->total;
                                    $growth = $prev > 0 ? (($screening->total - $prev) / $prev) * 100 : 100;
                                    @endphp
                                    {{ round($growth, 2) }}%
                                    @else
                                    -
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Grafik Aktivitas Harian
            const dailyCtx = document.getElementById('dailyScreeningsChart').getContext('2d');
            new Chart(dailyCtx, {
                type: 'bar',
                data: {
                    labels: @json($screenings - > pluck('display_date') - > reverse()),
                    datasets: [{
                        label: 'Screening Harian',
                        data: @json($screenings - > pluck('total') - > reverse()),
                        backgroundColor: 'rgba(79, 70, 229, 0.7)'
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    }
                }
            });

            // Grafik Distribusi Hasil
            const resultsCtx = document.getElementById('resultsDistributionChart').getContext('2d');
            new Chart(resultsCtx, {
                type: 'pie',
                data: {
                    labels: @json($results - > pluck('result')),
                    datasets: [{
                        data: @json($results - > pluck('count')),
                        backgroundColor: [
                            'rgba(75, 192, 192, 0.7)', // Hijau untuk rendah
                            'rgba(255, 206, 86, 0.7)', // Kuning untuk sedang
                            'rgba(255, 99, 132, 0.7)' // Merah untuk tinggi
                        ]
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false
                }
            });
        });
    </script>
    @endpush
</x-app-layout>