
<!DOCTYPE html>
<html lang="en">
<head>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <title>TikTok Sales Analysis</title>
</head>
<body class="bg-light">
    <div class="container mt-5">
        <div class="row mb-4">
            <div class="col-md-4">
                <div class="card bg-primary text-white p-3">
                    <h5>Total GMV</h5>
                    <h3>Rp {{ number_format($totalGmv, 0, ',', '.') }}</h3>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card bg-success text-white p-3">
                    <h5>Items Sold</h5>
                    <h3>{{ $totalItems }} Units</h3>
                </div>
            </div>
        </div>

        <div class="card p-4 shadow-sm">
            <form action="{{ route('sales.import') }}" method="POST" enctype="multipart/form-data" class="mb-3">
                @csrf
                <input type="file" name="csv_file" class="form-control mb-2" required>
                <button type="submit" class="btn btn-dark">Import Data CSV</button>
            </form>

            <div class="row mb-4">
    <div class="col-12">
        <div class="card shadow-sm">
            <div class="card-header bg-white"><strong>Tren Penjualan (GMV)</strong></div>
            <div class="card-body">
                <canvas id="salesChart" height="100"></canvas>
            </div>
        </div>
    </div>
</div>

            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Campaign</th>
                        <th>GMV</th>
                        <th>Items Sold</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($sales as $sale)
                    <tr>
                        <td>{{ $sale->date }}</td>
                        <td>{{ $sale->campaign }}</td>
                        <td>{{ number_format($sale->direct_gmv, 2) }}</td>
                        <td>{{ $sale->items_sold }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            {{ $sales->links() }}
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const ctx = document.getElementById('salesChart').getContext('2d');
    const salesChart = new Chart(ctx, {
        type: 'line', // Jenis grafik: garis
        data: {
            labels: {!! json_encode($labels) !!}, // Tanggal dari Controller
            datasets: [{
                label: 'Total GMV (Rp)',
                data: {!! json_encode($gmvValues) !!}, // Nilai GMV dari Controller
                borderColor: 'rgba(75, 192, 192, 1)',
                backgroundColor: 'rgba(75, 192, 192, 0.2)',
                borderWidth: 2,
                fill: true,
                tension: 0.3
            }]
        },
        options: {
            scales: {
                y: { beginAtZero: true }
            }
        }
    });
</script>


</body>
</html>