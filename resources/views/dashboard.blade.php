<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard Penjualan TikTok') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Alert Messages -->
            @if ($message = session('success'))
                <div class="mb-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded">
                    {{ $message }}
                </div>
            @endif

            @if ($message = session('error'))
                <div class="mb-4 p-4 bg-red-100 border border-red-400 text-red-700 rounded">
                    {{ $message }}
                </div>
            @endif

            <!-- Upload CSV Form -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6 text-gray-900">
                    <h3 class="text-lg font-semibold mb-4">Upload File CSV</h3>
                    <form action="{{ route('upload.csv') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="mb-4">
                            <label for="csv_file" class="block text-sm font-medium text-gray-700 mb-2">
                                Pilih File CSV
                            </label>
                            <input 
                                type="file" 
                                name="csv_file" 
                                id="csv_file" 
                                accept=".csv" 
                                required
                                class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500"
                            >
                            @error('csv_file')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                            <p class="mt-2 text-sm text-gray-500">Format: CSV dengan kolom Campaign, Date, Time, Direct GMV, Items sold, Customers, Viewers</p>
                        </div>
                        <button 
                            type="submit" 
                            class="px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700 transition"
                        >
                            Upload CSV
                        </button>
                    </form>
                </div>
            </div>

            <!-- Statistics Cards -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <div class="text-gray-500 text-sm font-medium">Total GMV</div>
                    <div class="text-3xl font-bold text-indigo-600 mt-2" id="totalGMV">Rp 0</div>
                </div>
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <div class="text-gray-500 text-sm font-medium">Total Items Sold</div>
                    <div class="text-3xl font-bold text-green-600 mt-2" id="totalItems">0</div>
                </div>
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <div class="text-gray-500 text-sm font-medium">Total Customers</div>
                    <div class="text-3xl font-bold text-blue-600 mt-2" id="totalCustomers">0</div>
                </div>
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <div class="text-gray-500 text-sm font-medium">Total Views</div>
                    <div class="text-3xl font-bold text-purple-600 mt-2" id="totalViewers">0</div>
                </div>
            </div>

            <!-- Charts Grid -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
                <!-- Direct GMV Chart -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <h3 class="text-lg font-semibold mb-4">Direct GMV Trend</h3>
                        <canvas id="gmvChart"></canvas>
                    </div>
                </div>

                <!-- Items Sold Chart -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <h3 class="text-lg font-semibold mb-4">Items Sold per Campaign</h3>
                        <canvas id="itemsChart"></canvas>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
                <!-- Customers Distribution Chart -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <h3 class="text-lg font-semibold mb-4">Customers Distribution</h3>
                        <canvas id="customersChart" style="max-height: 300px;"></canvas>
                    </div>
                </div>

                <!-- Viewers Chart with Interval Selector -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <div class="flex justify-between items-center mb-4">
                            <h3 class="text-lg font-semibold">Viewers Trend</h3>
                            <div class="flex gap-2">
                                <button onclick="updateViewersChart(1)" class="px-3 py-1 bg-indigo-600 text-white rounded text-sm font-semibold hover:bg-indigo-700 transition" id="btn-1h">Per Jam</button>
                                <button onclick="updateViewersChart(2)" class="px-3 py-1 bg-gray-300 text-gray-700 rounded text-sm font-semibold hover:bg-gray-400 transition" id="btn-2h">Per 2 Jam</button>
                                <button onclick="updateViewersChart(3)" class="px-3 py-1 bg-gray-300 text-gray-700 rounded text-sm font-semibold hover:bg-gray-400 transition" id="btn-3h">Per 3 Jam</button>
                            </div>
                        </div>
                        <canvas id="viewersChart"></canvas>
                        <div class="mt-3 text-xs text-gray-600">
                            <p><span class="inline-block w-3 h-3 bg-blue-500 mr-1"></span> Viewers (biru)</p>
                            <p><span class="inline-block w-3 h-3 bg-green-500 mr-1"></span> Customers (hijau)</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- GMV vs Customers Comparison -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6 text-gray-900">
                    <h3 class="text-lg font-semibold mb-4">GMV vs Customers Comparison</h3>
                    <canvas id="comparisonChart"></canvas>
                </div>
            </div>

            <!-- New Advanced Charts Section -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
                <!-- Top 15 Categories by GMV - Horizontal Bar Chart -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <h3 class="text-lg font-semibold mb-4">Top 15 Kampanye by GMV</h3>
                        <canvas id="top15Chart"></canvas>
                        <div class="mt-3 text-xs text-gray-600">
                            <p><span class="inline-block w-3 h-3 bg-blue-500 mr-1"></span> Total GMV per Kampanye</p>
                        </div>
                    </div>
                </div>

                <!-- Scatter Plot: Customers vs GMV -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <h3 class="text-lg font-semibold mb-4">Correlation: Customers vs GMV</h3>
                        <canvas id="scatterChart"></canvas>
                        <div class="mt-3 text-xs text-gray-600">
                            <p class="mb-1">X-axis: Rata-rata Customers | Y-axis: Rata-rata GMV</p>
                            <p><span class="inline-block w-3 h-3 bg-purple-500 mr-1"></span> Setiap titik = 1 Kampanye</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Treemap -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6 text-gray-900">
                    <h3 class="text-lg font-semibold mb-4">Treemap: Customers (Size) by GMV (Color)</h3>
                    <canvas id="treemapChart"></canvas>
                    <div class="mt-3 text-xs text-gray-600">
                        <p class="mb-1">Ukuran box = Jumlah Customers | Warna = Total GMV</p>
                        <p>Data Top 20 Kampanye berdasarkan GMV</p>
                    </div>
                </div>
            </div>

            <!-- Grouped Bar Chart with Toggle -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6 text-gray-900">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg font-semibold">GMV vs Customers by Kampanye</h3>
                        <button id="toggleGroupedChart" onclick="toggleGroupedChart()" class="px-4 py-2 bg-indigo-600 text-white rounded text-sm font-semibold hover:bg-indigo-700 transition">
                            Show All
                        </button>
                    </div>
                    <div style="position: relative; height: 400px; overflow-x: auto;" id="groupedChartContainer">
                        <canvas id="groupedChart" style="min-width: 800px;"></canvas>
                    </div>
                    <div class="mt-3 text-xs text-gray-600">
                        <p class="mb-1">Sumbu Kiri: GMV (Rp) | Sumbu Kanan: Customers</p>
                        <p><span class="inline-block w-3 h-3 bg-green-500 mr-1"></span> GMV (Rp)</p>
                        <p><span class="inline-block w-3 h-3 bg-orange-500 mr-1"></span> Customers</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-treemap@3"></script>
    <script>
        const data = @json($sales);
        let viewersChartInstance = null;
        
        if (data && data.length > 0) {
            // Format currency
            const formatCurrency = (num) => {
                return new Intl.NumberFormat('id-ID', {
                    style: 'currency',
                    currency: 'IDR',
                    minimumFractionDigits: 0
                }).format(num);
            };

            // Calculate totals
            const totalGMV = data.reduce((sum, d) => sum + (parseFloat(d.direct_gmv) || 0), 0);
            const totalItems = data.reduce((sum, d) => sum + (parseInt(d.items_sold) || 0), 0);
            const totalCustomers = data.reduce((sum, d) => sum + (parseInt(d.customers) || 0), 0);
            const totalViewers = data.reduce((sum, d) => sum + (parseInt(d.viewers) || 0), 0);

            // Update statistics
            document.getElementById('totalGMV').textContent = formatCurrency(totalGMV);
            document.getElementById('totalItems').textContent = totalItems.toLocaleString('id-ID');
            document.getElementById('totalCustomers').textContent = totalCustomers.toLocaleString('id-ID');
            document.getElementById('totalViewers').textContent = totalViewers.toLocaleString('id-ID');

            // Colors palette
            const colors = [
                'rgb(75, 192, 192)',
                'rgb(255, 99, 132)',
                'rgb(54, 162, 235)',
                'rgb(255, 206, 86)',
                'rgb(153, 102, 255)',
                'rgb(255, 159, 64)',
                'rgb(201, 203, 207)',
                'rgb(255, 192, 192)'
            ];

            // Function to group data by time interval
            function groupDataByInterval(data, intervalHours) {
                const grouped = {};
                data.forEach(d => {
                    const time = d.time || '00:00';
                    const [hour, min] = time.split(':').map(Number);
                    const roundedHour = Math.floor(hour / intervalHours) * intervalHours;
                    const key = String(roundedHour).padStart(2, '0') + ':00';
                    
                    if (!grouped[key]) {
                        grouped[key] = { viewers: 0, customers: 0, count: 0 };
                    }
                    grouped[key].viewers += parseInt(d.viewers) || 0;
                    grouped[key].customers += parseInt(d.customers) || 0;
                    grouped[key].count += 1;
                });
                return grouped;
            }

            // Function to update viewers chart with different intervals
            function updateViewersChart(intervalHours) {
                const grouped = groupDataByInterval(data, intervalHours);
                const labels = Object.keys(grouped).sort();
                const viewersData = labels.map(label => grouped[label].viewers);
                const customersData = labels.map(label => grouped[label].customers);

                if (viewersChartInstance) {
                    viewersChartInstance.destroy();
                }

                // Update button styles
                document.getElementById('btn-1h').classList.remove('bg-indigo-600', 'text-white');
                document.getElementById('btn-1h').classList.add('bg-gray-300', 'text-gray-700');
                document.getElementById('btn-2h').classList.remove('bg-indigo-600', 'text-white');
                document.getElementById('btn-2h').classList.add('bg-gray-300', 'text-gray-700');
                document.getElementById('btn-3h').classList.remove('bg-indigo-600', 'text-white');
                document.getElementById('btn-3h').classList.add('bg-gray-300', 'text-gray-700');

                if (intervalHours === 1) {
                    document.getElementById('btn-1h').classList.remove('bg-gray-300', 'text-gray-700');
                    document.getElementById('btn-1h').classList.add('bg-indigo-600', 'text-white');
                } else if (intervalHours === 2) {
                    document.getElementById('btn-2h').classList.remove('bg-gray-300', 'text-gray-700');
                    document.getElementById('btn-2h').classList.add('bg-indigo-600', 'text-white');
                } else if (intervalHours === 3) {
                    document.getElementById('btn-3h').classList.remove('bg-gray-300', 'text-gray-700');
                    document.getElementById('btn-3h').classList.add('bg-indigo-600', 'text-white');
                }

                const viewersCtx = document.getElementById('viewersChart').getContext('2d');
                viewersChartInstance = new Chart(viewersCtx, {
                    type: 'line',
                    data: {
                        labels: labels,
                        datasets: [
                            {
                                label: 'Viewers (Orang)',
                                data: viewersData,
                                borderColor: 'rgb(54, 162, 235)',
                                backgroundColor: 'rgba(54, 162, 235, 0.1)',
                                borderWidth: 3,
                                tension: 0.4,
                                fill: true,
                                pointRadius: 5,
                                pointBackgroundColor: 'rgb(54, 162, 235)',
                                pointBorderColor: '#fff',
                                pointBorderWidth: 2
                            },
                            {
                                label: 'Customers (Orang)',
                                data: customersData,
                                borderColor: 'rgb(75, 192, 75)',
                                backgroundColor: 'rgba(75, 192, 75, 0.1)',
                                borderWidth: 3,
                                tension: 0.4,
                                fill: true,
                                pointRadius: 5,
                                pointBackgroundColor: 'rgb(75, 192, 75)',
                                pointBorderColor: '#fff',
                                pointBorderWidth: 2
                            }
                        ]
                    },
                    options: {
                        responsive: true,
                        interaction: { mode: 'index', intersect: false },
                        plugins: {
                            legend: { 
                                display: true, 
                                position: 'top',
                                labels: {
                                    usePointStyle: true,
                                    font: { size: 13 },
                                    padding: 15
                                }
                            },
                            tooltip: {
                                backgroundColor: 'rgba(0,0,0,0.8)',
                                titleFont: { size: 14 },
                                bodyFont: { size: 13 },
                                padding: 12,
                                displayColors: true,
                                callbacks: {
                                    label: function(context) {
                                        let label = context.dataset.label || '';
                                        if (label) {
                                            label += ': ';
                                        }
                                        label += context.parsed.y.toLocaleString('id-ID') + ' orang';
                                        return label;
                                    }
                                }
                            }
                        },
                        scales: {
                            y: { 
                                beginAtZero: true,
                                ticks: {
                                    callback: function(value) {
                                        return value.toLocaleString('id-ID');
                                    }
                                },
                                title: {
                                    display: true,
                                    text: 'Jumlah'
                                }
                            },
                            x: {
                                title: {
                                    display: true,
                                    text: 'Jam'
                                }
                            }
                        }
                    }
                });
            }

            // 1. Direct GMV Trend Chart with Legend
            const gmvLabels = data.map(d => d.time || 'N/A');
            const gmvValues = data.map(d => d.direct_gmv || 0);

            new Chart(document.getElementById('gmvChart'), {
                type: 'line',
                data: {
                    labels: gmvLabels,
                    datasets: [{
                        label: 'Direct GMV (Rp)',
                        data: gmvValues,
                        borderColor: 'rgb(75, 192, 192)',
                        backgroundColor: 'rgba(75, 192, 192, 0.1)',
                        borderWidth: 3,
                        tension: 0.4,
                        fill: true,
                        pointRadius: 5,
                        pointBackgroundColor: 'rgb(75, 192, 192)',
                        pointBorderColor: '#fff',
                        pointBorderWidth: 2
                    }]
                },
                options: {
                    responsive: true,
                    interaction: { mode: 'index', intersect: false },
                    plugins: {
                        legend: { 
                            display: true, 
                            position: 'top',
                            labels: {
                                usePointStyle: true,
                                font: { size: 13 },
                                padding: 15
                            }
                        },
                        tooltip: {
                            backgroundColor: 'rgba(0,0,0,0.8)',
                            titleFont: { size: 14 },
                            bodyFont: { size: 13 },
                            padding: 12,
                            callbacks: {
                                label: function(context) {
                                    return 'GMV: Rp ' + context.parsed.y.toLocaleString('id-ID');
                                }
                            }
                        }
                    },
                    scales: {
                        y: { 
                            beginAtZero: true,
                            ticks: {
                                callback: function(value) {
                                    return 'Rp ' + (value / 1000000).toFixed(1) + 'M';
                                }
                            },
                            title: {
                                display: true,
                                text: 'Nilai (Rp)'
                            }
                        },
                        x: {
                            title: {
                                display: true,
                                text: 'Waktu'
                            }
                        }
                    }
                }
            });

            // 2. Items Sold Bar Chart with Legend
            const campaignGroups = {};
            data.forEach(d => {
                const campaign = d.campaign || 'Unknown';
                if (!campaignGroups[campaign]) {
                    campaignGroups[campaign] = 0;
                }
                campaignGroups[campaign] += parseInt(d.items_sold) || 0;
            });

            new Chart(document.getElementById('itemsChart'), {
                type: 'bar',
                data: {
                    labels: Object.keys(campaignGroups),
                    datasets: [{
                        label: 'Item Terjual (Unit)',
                        data: Object.values(campaignGroups),
                        backgroundColor: 'rgba(54, 162, 235, 0.8)',
                        borderColor: 'rgb(54, 162, 235)',
                        borderWidth: 2,
                        borderRadius: 5
                    }]
                },
                options: {
                    responsive: true,
                    indexAxis: 'x',
                    plugins: {
                        legend: { 
                            display: true, 
                            position: 'top',
                            labels: {
                                font: { size: 13 },
                                padding: 15
                            }
                        },
                        tooltip: {
                            backgroundColor: 'rgba(0,0,0,0.8)',
                            titleFont: { size: 14 },
                            bodyFont: { size: 13 },
                            padding: 12,
                            callbacks: {
                                label: function(context) {
                                    return context.dataset.label + ': ' + context.parsed.y.toLocaleString('id-ID') + ' unit';
                                }
                            }
                        }
                    },
                    scales: {
                        y: { 
                            beginAtZero: true,
                            ticks: {
                                callback: function(value) {
                                    return value.toLocaleString('id-ID');
                                }
                            },
                            title: {
                                display: true,
                                text: 'Jumlah (Unit)'
                            }
                        },
                        x: {
                            title: {
                                display: true,
                                text: 'Kampanye'
                            }
                        }
                    }
                }
            });

            // 3. Customers Distribution Pie Chart with Legend
            new Chart(document.getElementById('customersChart'), {
                type: 'doughnut',
                data: {
                    labels: gmvLabels,
                    datasets: [{
                        label: 'Customers per Jam',
                        data: data.map(d => d.customers || 0),
                        backgroundColor: colors,
                        borderColor: '#fff',
                        borderWidth: 2
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: { 
                            position: 'right',
                            labels: {
                                font: { size: 12 },
                                padding: 10
                            }
                        },
                        tooltip: {
                            backgroundColor: 'rgba(0,0,0,0.8)',
                            titleFont: { size: 14 },
                            bodyFont: { size: 13 },
                            padding: 12,
                            callbacks: {
                                label: function(context) {
                                    return context.label + ': ' + context.parsed + ' customer';
                                }
                            }
                        }
                    }
                }
            });

            // 4. Initial Viewers Chart (Per Jam / 1 Hour)
            updateViewersChart(1);

            // 5. GMV vs Customers Comparison Chart with Legend
            new Chart(document.getElementById('comparisonChart'), {
                type: 'radar',
                data: {
                    labels: gmvLabels,
                    datasets: [
                        {
                            label: 'GMV (Rp Jutaan)',
                            data: data.map(d => Math.round(d.direct_gmv / 1000000)),
                            borderColor: 'rgb(75, 192, 192)',
                            backgroundColor: 'rgba(75, 192, 192, 0.2)',
                            fill: true,
                            borderWidth: 2,
                            pointRadius: 3
                        },
                        {
                            label: 'Customers (Orang)',
                            data: data.map(d => d.customers || 0),
                            borderColor: 'rgb(255, 99, 132)',
                            backgroundColor: 'rgba(255, 99, 132, 0.2)',
                            fill: true,
                            borderWidth: 2,
                            pointRadius: 3
                        }
                    ]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: { 
                            position: 'top',
                            labels: {
                                font: { size: 13 },
                                padding: 15
                            }
                        },
                        tooltip: {
                            backgroundColor: 'rgba(0,0,0,0.8)',
                            titleFont: { size: 14 },
                            bodyFont: { size: 13 },
                            padding: 12
                        }
                    },
                    scales: {
                        r: {
                            beginAtZero: true,
                            ticks: {
                                font: { size: 11 }
                            }
                        }
                    }
                }
            });
        } else {
            document.querySelectorAll('canvas').forEach(canvas => {
                canvas.parentElement.innerHTML = '<p class="text-gray-500 text-center py-4">Tidak ada data. Silakan upload file CSV.</p>';
            });
        }

        // NEW CHARTS - TOP 15, SCATTER, TREEMAP, GROUPED BAR

        // 6. Top 15 Categories by GMV - Horizontal Bar Chart
        const top15Data = @json($top15ByGMV);
        if (top15Data && top15Data.length > 0) {
            const top15Labels = top15Data.map(d => d.campaign);
            const top15Values = top15Data.map(d => d.total_gmv);

            new Chart(document.getElementById('top15Chart'), {
                type: 'bar',
                data: {
                    labels: top15Labels,
                    datasets: [{
                        label: 'Total GMV (Rp)',
                        data: top15Values,
                        backgroundColor: 'rgba(59, 130, 246, 0.8)',
                        borderColor: 'rgb(59, 130, 246)',
                        borderWidth: 2,
                        borderRadius: 5
                    }]
                },
                options: {
                    indexAxis: 'y',
                    responsive: true,
                    plugins: {
                        legend: { display: true, position: 'top' },
                        tooltip: {
                            backgroundColor: 'rgba(0,0,0,0.8)',
                            padding: 12,
                            callbacks: {
                                label: function(context) {
                                    return 'GMV: Rp ' + context.parsed.x.toLocaleString('id-ID');
                                }
                            }
                        }
                    },
                    scales: {
                        x: {
                            beginAtZero: true,
                            ticks: {
                                callback: function(value) {
                                    return 'Rp ' + (value / 1000000).toFixed(0) + 'M';
                                }
                            }
                        }
                    }
                }
            });
        }

        // 7. Scatter Plot: Customers vs GMV
        const scatterChartData = @json($scatterData);
        if (scatterChartData && scatterChartData.length > 0) {
            const scatterPoints = scatterChartData.map(d => ({
                x: parseFloat(d.avg_customers) || 0,
                y: parseFloat(d.avg_gmv) || 0,
                campaign: d.campaign
            }));

            new Chart(document.getElementById('scatterChart'), {
                type: 'scatter',
                data: {
                    datasets: [{
                        label: 'Campaign Correlation',
                        data: scatterPoints,
                        backgroundColor: 'rgba(168, 85, 247, 0.6)',
                        borderColor: 'rgb(168, 85, 247)',
                        borderWidth: 2,
                        pointRadius: 6,
                        pointHoverRadius: 8
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: { display: true, position: 'top' },
                        tooltip: {
                            backgroundColor: 'rgba(0,0,0,0.8)',
                            padding: 12,
                            callbacks: {
                                label: function(context) {
                                    const dataPoint = context.raw;
                                    return 'Customers: ' + Math.round(dataPoint.x) + ' | GMV: Rp ' + Math.round(dataPoint.y).toLocaleString('id-ID');
                                },
                                afterLabel: function(context) {
                                    const index = context.dataIndex;
                                    return scatterChartData[index] ? scatterChartData[index].campaign : '';
                                }
                            }
                        }
                    },
                    scales: {
                        x: {
                            beginAtZero: true,
                            title: {
                                display: true,
                                text: 'Rata-rata Customers'
                            }
                        },
                        y: {
                            beginAtZero: true,
                            title: {
                                display: true,
                                text: 'Rata-rata GMV (Rp)'
                            },
                            ticks: {
                                callback: function(value) {
                                    return 'Rp ' + (value / 1000000).toFixed(0) + 'M';
                                }
                            }
                        }
                    }
                }
            });
        }

        // 8. Grouped Bar Chart with Toggle (Top 10 / All) and Dual Y-Axes
        const groupedChartData = @json($groupedChartData);
        let groupedChartInstance = null;
        let showTop10 = true;

        function renderGroupedChart(showOnlyTop10 = true) {
            if (groupedChartInstance) {
                groupedChartInstance.destroy();
            }

            let chartData = groupedChartData;
            if (showOnlyTop10) {
                chartData = groupedChartData.slice(0, 10);
                document.getElementById('toggleGroupedChart').textContent = 'Show All';
            } else {
                document.getElementById('toggleGroupedChart').textContent = 'Show Top 10';
            }

            const groupLabels = chartData.map(d => d.campaign);
            const gmvData = chartData.map(d => parseFloat(d.total_gmv) || 0);
            const customersData = chartData.map(d => parseInt(d.total_customers) || 0);

            const ctx = document.getElementById('groupedChart').getContext('2d');
            groupedChartInstance = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: groupLabels,
                    datasets: [
                        {
                            label: 'GMV (Rp)',
                            data: gmvData,
                            backgroundColor: 'rgba(34, 197, 94, 0.8)',
                            borderColor: 'rgb(34, 197, 94)',
                            borderWidth: 2,
                            borderRadius: 5,
                            yAxisID: 'y'
                        },
                        {
                            label: 'Customers',
                            data: customersData,
                            backgroundColor: 'rgba(249, 115, 22, 0.8)',
                            borderColor: 'rgb(249, 115, 22)',
                            borderWidth: 2,
                            borderRadius: 5,
                            yAxisID: 'y1'
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    interaction: { mode: 'index', intersect: false },
                    plugins: {
                        legend: { position: 'top' },
                        tooltip: {
                            backgroundColor: 'rgba(0,0,0,0.8)',
                            padding: 12,
                            callbacks: {
                                afterLabel: function(context) {
                                    if (context.dataset.label === 'GMV (Rp)') {
                                        return 'Rp ' + context.parsed.y.toLocaleString('id-ID');
                                    }
                                    return context.parsed.y.toLocaleString('id-ID') + ' orang';
                                }
                            }
                        }
                    },
                    scales: {
                        y: {
                            type: 'linear',
                            display: true,
                            position: 'left',
                            beginAtZero: true,
                            title: {
                                display: true,
                                text: 'GMV (Rp)',
                                color: 'rgb(34, 197, 94)'
                            },
                            ticks: {
                                color: 'rgb(34, 197, 94)',
                                callback: function(value) {
                                    return 'Rp ' + (value / 1000000).toFixed(0) + 'M';
                                }
                            }
                        },
                        y1: {
                            type: 'linear',
                            display: true,
                            position: 'right',
                            beginAtZero: true,
                            title: {
                                display: true,
                                text: 'Customers',
                                color: 'rgb(249, 115, 22)'
                            },
                            ticks: {
                                color: 'rgb(249, 115, 22)',
                                callback: function(value) {
                                    return value.toLocaleString('id-ID');
                                }
                            },
                            grid: {
                                drawOnChartArea: false
                            }
                        }
                    }
                }
            });
        }

        // Initial render with Top 10
        if (groupedChartData && groupedChartData.length > 0) {
            renderGroupedChart(true);
        }

        // Toggle function for grouped chart
        function toggleGroupedChart() {
            showTop10 = !showTop10;
            renderGroupedChart(showTop10);
        }
    </script>
</x-app-layout>
