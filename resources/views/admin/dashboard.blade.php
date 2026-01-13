@extends('admin.layout')

@section('content')
    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <div class="bg-gradient-to-br from-blue-900 to-blue-800 rounded-lg p-6 shadow-lg border border-blue-700">
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-blue-200 text-sm font-medium">Total User</p>
                    <h3 class="text-4xl font-bold text-white mt-2">{{ $totalUsers }}</h3>
                    <p class="text-blue-300 text-xs mt-2">{{ $totalAdmins }} Admin • {{ $totalRegularUsers }} User</p>
                </div>
                <span class="text-4xl">👥</span>
            </div>
        </div>

        <div class="bg-gradient-to-br from-purple-900 to-purple-800 rounded-lg p-6 shadow-lg border border-purple-700">
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-purple-200 text-sm font-medium">File CSV</p>
                    <h3 class="text-4xl font-bold text-white mt-2">{{ $totalFiles }}</h3>
                    <p class="text-purple-300 text-xs mt-2">{{ $filesProcessed }} Processed • {{ $filesFailed }} Failed</p>
                </div>
                <span class="text-4xl">📁</span>
            </div>
        </div>

        <div class="bg-gradient-to-br from-green-900 to-green-800 rounded-lg p-6 shadow-lg border border-green-700">
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-green-200 text-sm font-medium">Total Penjualan</p>
                    <h3 class="text-4xl font-bold text-white mt-2">{{ $totalSales }}</h3>
                    <p class="text-green-300 text-xs mt-2">Records dari CSV</p>
                </div>
                <span class="text-4xl">📊</span>
            </div>
        </div>

        <div class="bg-gradient-to-br from-orange-900 to-orange-800 rounded-lg p-6 shadow-lg border border-orange-700">
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-orange-200 text-sm font-medium">Ukuran File</p>
                    <h3 class="text-3xl font-bold text-white mt-2">{{ number_format($totalFileSize / (1024*1024), 2) }} MB</h3>
                    <p class="text-orange-300 text-xs mt-2">Total Storage</p>
                </div>
                <span class="text-4xl">💾</span>
            </div>
        </div>
    </div>

    <!-- Charts Section -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
        <!-- Daily GMV Trend -->
        <div class="bg-gray-800 rounded-lg p-6 shadow-lg border border-gray-700">
            <h3 class="text-lg font-bold text-white mb-4 flex items-center">
                <span class="text-indigo-400 mr-2">📈</span> GMV Trend (30 Hari Terakhir)
            </h3>
            <canvas id="gmvChart"></canvas>
        </div>

        <!-- Items Sold Trend -->
        <div class="bg-gray-800 rounded-lg p-6 shadow-lg border border-gray-700">
            <h3 class="text-lg font-bold text-white mb-4 flex items-center">
                <span class="text-green-400 mr-2">🛍️</span> Item Terjual Trend
            </h3>
            <canvas id="itemsChart"></canvas>
        </div>
    </div>

    <!-- Campaign Performance -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
        <div class="bg-gray-800 rounded-lg p-6 shadow-lg border border-gray-700">
            <h3 class="text-lg font-bold text-white mb-4 flex items-center">
                <span class="text-purple-400 mr-2">🎯</span> Top 10 Campaign by GMV
            </h3>
            <canvas id="campaignChart"></canvas>
        </div>

        <!-- File Upload Distribution -->
        <div class="bg-gray-800 rounded-lg p-6 shadow-lg border border-gray-700">
            <h3 class="text-lg font-bold text-white mb-4 flex items-center">
                <span class="text-pink-400 mr-2">📤</span> Upload Distribution by User
            </h3>
            <canvas id="userUploadsChart"></canvas>
        </div>
    </div>

    <!-- Key Insights -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
        <div class="bg-gray-800 rounded-lg p-6 shadow-lg border border-gray-700">
            <h4 class="text-lg font-bold text-white mb-4">📊 Insight Kunci</h4>
            <ul class="space-y-3 text-sm">
                @if(count($topCampaigns) > 0)
                    <li class="flex justify-between items-center">
                        <span class="text-gray-300">Top Campaign:</span>
                        <span class="font-semibold text-indigo-400">{{ $topCampaigns[0]->campaign ?? 'N/A' }}</span>
                    </li>
                    <li class="flex justify-between items-center">
                        <span class="text-gray-300">Top Campaign GMV:</span>
                        <span class="font-semibold text-green-400">Rp {{ number_format($topCampaigns[0]->total_gmv, 0, ',', '.') }}</span>
                    </li>
                    <li class="flex justify-between items-center">
                        <span class="text-gray-300">Avg GMV:</span>
                        <span class="font-semibold text-orange-400">Rp {{ number_format($topCampaigns[0]->avg_gmv, 0, ',', '.') }}</span>
                    </li>
                @else
                    <li class="text-gray-400">Belum ada data kampanye</li>
                @endif
            </ul>
        </div>

        <div class="bg-gray-800 rounded-lg p-6 shadow-lg border border-gray-700">
            <h4 class="text-lg font-bold text-white mb-4">👥 User Activity</h4>
            <ul class="space-y-3 text-sm">
                <li class="flex justify-between items-center">
                    <span class="text-gray-300">Total User:</span>
                    <span class="font-semibold text-blue-400">{{ $totalUsers }}</span>
                </li>
                <li class="flex justify-between items-center">
                    <span class="text-gray-300">Admin:</span>
                    <span class="font-semibold text-purple-400">{{ $totalAdmins }}</span>
                </li>
                <li class="flex justify-between items-center">
                    <span class="text-gray-300">Regular User:</span>
                    <span class="font-semibold text-green-400">{{ $totalRegularUsers }}</span>
                </li>
                <li class="flex justify-between items-center">
                    <span class="text-gray-300">Top Uploader:</span>
                    <span class="font-semibold text-pink-400">{{ $topUploadUsers[0]->name ?? 'N/A' }} ({{ $topUploadUsers[0]->uploaded_files_count ?? 0 }})</span>
                </li>
            </ul>
        </div>

        <div class="bg-gray-800 rounded-lg p-6 shadow-lg border border-gray-700">
            <h4 class="text-lg font-bold text-white mb-4">📁 File Statistics</h4>
            <ul class="space-y-3 text-sm">
                <li class="flex justify-between items-center">
                    <span class="text-gray-300">Total File:</span>
                    <span class="font-semibold text-indigo-400">{{ $totalFiles }}</span>
                </li>
                <li class="flex justify-between items-center">
                    <span class="text-gray-300">Processed:</span>
                    <span class="font-semibold text-green-400">{{ $filesProcessed }}</span>
                </li>
                <li class="flex justify-between items-center">
                    <span class="text-gray-300">Failed:</span>
                    <span class="font-semibold text-red-400">{{ $filesFailed }}</span>
                </li>
                <li class="flex justify-between items-center">
                    <span class="text-gray-300">Total Size:</span>
                    <span class="font-semibold text-orange-400">{{ number_format($totalFileSize / (1024*1024), 2) }} MB</span>
                </li>
            </ul>
        </div>
    </div>

    <!-- Recent Files -->
    <div class="bg-gray-800 rounded-lg p-6 shadow-lg border border-gray-700 mb-8">
        <h3 class="text-lg font-bold text-white mb-4 flex items-center">
            <span class="text-cyan-400 mr-2">⏱️</span> File Terbaru
        </h3>
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="border-b border-gray-700">
                        <th class="px-4 py-3 text-left text-gray-300">Nama File</th>
                        <th class="px-4 py-3 text-left text-gray-300">User</th>
                        <th class="px-4 py-3 text-left text-gray-300">Ukuran</th>
                        <th class="px-4 py-3 text-left text-gray-300">Rows</th>
                        <th class="px-4 py-3 text-left text-gray-300">Status</th>
                        <th class="px-4 py-3 text-left text-gray-300">Tanggal</th>
                        <th class="px-4 py-3 text-left text-gray-300">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($recentFiles as $file)
                        <tr class="border-b border-gray-700 hover:bg-gray-700 transition">
                            <td class="px-4 py-3 text-white">{{ $file->original_name }}</td>
                            <td class="px-4 py-3 text-gray-300">{{ $file->user->name ?? 'Unknown' }}</td>
                            <td class="px-4 py-3 text-gray-300">{{ number_format($file->file_size / 1024, 2) }} KB</td>
                            <td class="px-4 py-3 text-gray-300">{{ $file->row_count }}</td>
                            <td class="px-4 py-3">
                                <span class="px-3 py-1 rounded-full text-xs font-semibold
                                    @if($file->status === 'processed') bg-green-900 text-green-200
                                    @elseif($file->status === 'failed') bg-red-900 text-red-200
                                    @else bg-yellow-900 text-yellow-200
                                    @endif">
                                    {{ ucfirst($file->status) }}
                                </span>
                            </td>
                            <td class="px-4 py-3 text-gray-300">{{ $file->created_at->format('d M Y H:i') }}</td>
                            <td class="px-4 py-3">
                                <a href="{{ route('admin.files.show', $file) }}" class="text-indigo-400 hover:text-indigo-300">View</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-4 py-3 text-center text-gray-400">Belum ada file</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <script>
        // Colors
        const colors = {
            primary: '#6366f1',
            success: '#10b981',
            danger: '#ef4444',
            warning: '#f59e0b',
            purple: '#a855f7',
            blue: '#3b82f6',
            pink: '#ec4899'
        };

        // Daily GMV Chart
        const gmvCtx = document.getElementById('gmvChart').getContext('2d');
        new Chart(gmvCtx, {
            type: 'line',
            data: {
                labels: @json($chartLabels),
                datasets: [{
                    label: 'Total GMV',
                    data: @json($chartGMV),
                    borderColor: colors.primary,
                    backgroundColor: 'rgba(99, 102, 241, 0.1)',
                    tension: 0.4,
                    fill: true,
                    pointRadius: 4,
                    pointBackgroundColor: colors.primary,
                    pointBorderColor: '#fff',
                    pointBorderWidth: 2,
                    pointHoverRadius: 6
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: true,
                plugins: {
                    legend: {
                        labels: { color: '#e5e7eb' }
                    }
                },
                scales: {
                    y: {
                        ticks: { color: '#9ca3af' },
                        grid: { color: '#374151' },
                        beginAtZero: true
                    },
                    x: {
                        ticks: { color: '#9ca3af' },
                        grid: { color: '#374151' }
                    }
                }
            }
        });

        // Items Sold Chart
        const itemsCtx = document.getElementById('itemsChart').getContext('2d');
        new Chart(itemsCtx, {
            type: 'bar',
            data: {
                labels: @json($chartLabels),
                datasets: [{
                    label: 'Item Terjual',
                    data: @json($chartItems),
                    backgroundColor: colors.success,
                    borderColor: '#059669',
                    borderWidth: 1,
                    borderRadius: 4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: true,
                plugins: {
                    legend: {
                        labels: { color: '#e5e7eb' }
                    }
                },
                scales: {
                    y: {
                        ticks: { color: '#9ca3af' },
                        grid: { color: '#374151' },
                        beginAtZero: true
                    },
                    x: {
                        ticks: { color: '#9ca3af' },
                        grid: { color: '#374151' }
                    }
                }
            }
        });

        // Campaign Chart
        const campaignCtx = document.getElementById('campaignChart').getContext('2d');
        new Chart(campaignCtx, {
            type: 'horizontalBar',
            data: {
                labels: @json($campaignLabels),
                datasets: [{
                    label: 'Total GMV',
                    data: @json($campaignGMV),
                    backgroundColor: [
                        colors.primary,
                        colors.purple,
                        colors.pink,
                        colors.blue,
                        colors.success,
                        colors.warning,
                        '#f97316',
                        '#06b6d4',
                        '#8b5cf6',
                        '#ec4899'
                    ],
                    borderRadius: 4
                }]
            },
            options: {
                indexAxis: 'y',
                responsive: true,
                maintainAspectRatio: true,
                plugins: {
                    legend: {
                        labels: { color: '#e5e7eb' }
                    }
                },
                scales: {
                    y: {
                        ticks: { color: '#9ca3af' },
                        grid: { color: '#374151' }
                    },
                    x: {
                        ticks: { color: '#9ca3af' },
                        grid: { color: '#374151' },
                        beginAtZero: true
                    }
                }
            }
        });

        // User Uploads Chart
        const userUploadsCtx = document.getElementById('userUploadsChart').getContext('2d');
        new Chart(userUploadsCtx, {
            type: 'doughnut',
            data: {
                labels: @json($userNames),
                datasets: [{
                    data: @json($userFiles),
                    backgroundColor: [
                        colors.primary,
                        colors.success,
                        colors.warning,
                        colors.danger,
                        colors.purple,
                        colors.pink,
                        colors.blue,
                        '#f97316'
                    ],
                    borderColor: '#1f2937',
                    borderWidth: 2
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: true,
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: { color: '#e5e7eb' }
                    }
                }
            }
        });
    </script>
@endsection
