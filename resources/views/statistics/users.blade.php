<x-app-layout>
    <div class="py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="mb-8">
                <h1 class="text-3xl font-bold text-gray-900">Statistik Pengguna</h1>
                <p class="mt-2 text-sm text-gray-600">Data perkembangan pengguna sistem</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-10">
                <div class="bg-white p-6 rounded-lg shadow">
                    <h3 class="text-lg font-medium text-gray-500">Total Pengguna</h3>
                    <p class="text-3xl font-bold text-blue-600">{{ number_format($totalUsers) }}</p>
                </div>
                <div class="bg-white p-6 rounded-lg shadow">
                    <h3 class="text-lg font-medium text-gray-500">Pengguna Aktif (30 hari)</h3>
                    <p class="text-3xl font-bold text-green-600">{{ number_format($activeUsers) }}</p>
                </div>
                <div class="bg-white p-6 rounded-lg shadow">
                    <h3 class="text-lg font-medium text-gray-500">Pengguna Baru (30 hari)</h3>
                    <p class="text-3xl font-bold text-purple-600">{{ number_format($newUsers) }}</p>
                </div>
            </div>

            <div class="bg-white overflow-hidden shadow rounded-lg mb-10">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h2 class="text-xl font-semibold text-gray-800">Pertumbuhan Pengguna Bulanan</h2>
                </div>
                <div class="p-6">
                    <div class="h-96">
                        <canvas id="userGrowthChart"></canvas>
                    </div>
                </div>
            </div>

            <div class="bg-white overflow-hidden shadow rounded-lg">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h2 class="text-xl font-semibold text-gray-800">Detail Pendaftaran Pengguna</h2>
                </div>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Bulan</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Jumlah</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Persentase</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($users as $user)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap">{{ $user->display_month }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">{{ $user->total }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    {{ round(($user->total / $totalUsers) * 100, 2) }}%
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
            const ctx = document.getElementById('userGrowthChart').getContext('2d');
            new Chart(ctx, {
                type: 'line',
                data: {
                    labels: @json($users - > pluck('display_month')),
                    datasets: [{
                        label: 'Pendaftaran Pengguna',
                        data: @json($users - > pluck('total')),
                        borderColor: 'rgb(79, 70, 229)',
                        backgroundColor: 'rgba(79, 70, 229, 0.1)',
                        tension: 0.3,
                        fill: true
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                precision: 0
                            }
                        }
                    }
                }
            });
        });
    </script>
    @endpush
</x-app-layout>