@extends('admin.layout')

@section('content')
    <div class="max-w-4xl mx-auto">
        <div class="flex justify-between items-start mb-6">
            <div>
                <h2 class="text-3xl font-bold text-white mb-2">Detail Penjualan</h2>
                <p class="text-gray-400">Kampanye: <span class="font-semibold text-indigo-400">{{ $sale->campaign }}</span></p>
            </div>
            <div class="flex gap-2">
                <a href="{{ route('admin.sales.edit', $sale) }}" class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg font-semibold transition">
                    Edit
                </a>
                <form action="{{ route('admin.sales.destroy', $sale) }}" method="POST" class="inline" onsubmit="return confirm('Yakin ingin menghapus?');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded-lg font-semibold transition">
                        Hapus
                    </button>
                </form>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Basic Information -->
            <div class="bg-gray-800 rounded-lg p-6 shadow-lg border border-gray-700">
                <h3 class="text-xl font-bold text-white mb-4">Informasi Dasar</h3>
                <div class="space-y-4">
                    <div>
                        <p class="text-gray-400 text-sm mb-1">Kampanye</p>
                        <p class="text-white font-semibold text-lg">{{ $sale->campaign }}</p>
                    </div>
                    <div>
                        <p class="text-gray-400 text-sm mb-1">Tanggal</p>
                        <p class="text-white font-semibold text-lg">{{ \Carbon\Carbon::parse($sale->date)->format('d F Y') }}</p>
                    </div>
                    <div>
                        <p class="text-gray-400 text-sm mb-1">Jam</p>
                        <p class="text-white font-semibold text-lg">{{ $sale->time }}</p>
                    </div>
                </div>
            </div>

            <!-- Financial Data -->
            <div class="bg-gray-800 rounded-lg p-6 shadow-lg border border-gray-700">
                <h3 class="text-xl font-bold text-white mb-4">Data Finansial</h3>
                <div class="space-y-4">
                    <div>
                        <p class="text-gray-400 text-sm mb-1">Direct GMV</p>
                        <p class="text-green-400 font-bold text-2xl">Rp {{ number_format($sale->direct_gmv, 0, ',', '.') }}</p>
                    </div>
                    <div>
                        <p class="text-gray-400 text-sm mb-1">Rata-rata GMV per Item</p>
                        @if($sale->items_sold > 0)
                            <p class="text-blue-400 font-semibold text-lg">Rp {{ number_format($sale->direct_gmv / $sale->items_sold, 0, ',', '.') }}</p>
                        @else
                            <p class="text-blue-400 font-semibold text-lg">N/A</p>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Sales Metrics -->
            <div class="bg-gray-800 rounded-lg p-6 shadow-lg border border-gray-700">
                <h3 class="text-xl font-bold text-white mb-4">Metrik Penjualan</h3>
                <div class="space-y-4">
                    <div>
                        <p class="text-gray-400 text-sm mb-1">Item Terjual</p>
                        <p class="text-blue-400 font-bold text-2xl">{{ number_format($sale->items_sold, 0, ',', '.') }}</p>
                    </div>
                    <div>
                        <p class="text-gray-400 text-sm mb-1">Customer</p>
                        <p class="text-purple-400 font-bold text-2xl">{{ number_format($sale->customers, 0, ',', '.') }}</p>
                    </div>
                </div>
            </div>

            <!-- Engagement Metrics -->
            <div class="bg-gray-800 rounded-lg p-6 shadow-lg border border-gray-700">
                <h3 class="text-xl font-bold text-white mb-4">Metrik Engagement</h3>
                <div class="space-y-4">
                    <div>
                        <p class="text-gray-400 text-sm mb-1">Viewers</p>
                        <p class="text-pink-400 font-bold text-2xl">{{ number_format($sale->viewers, 0, ',', '.') }}</p>
                    </div>
                    <div>
                        <p class="text-gray-400 text-sm mb-1">Konversi (Customer/Viewers)</p>
                        @if($sale->viewers > 0)
                            <p class="text-orange-400 font-semibold text-lg">{{ number_format(($sale->customers / $sale->viewers) * 100, 2) }}%</p>
                        @else
                            <p class="text-orange-400 font-semibold text-lg">N/A</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Statistics Summary -->
        <div class="bg-gray-800 rounded-lg p-6 shadow-lg border border-gray-700 mt-6">
            <h3 class="text-xl font-bold text-white mb-4">Ringkasan Statistik</h3>
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 text-sm">
                <div>
                    <p class="text-gray-400">Items per Customer</p>
                    @if($sale->customers > 0)
                        <p class="text-white font-semibold text-lg">{{ number_format($sale->items_sold / $sale->customers, 2) }}</p>
                    @else
                        <p class="text-white font-semibold text-lg">N/A</p>
                    @endif
                </div>
                <div>
                    <p class="text-gray-400">Viewers per Customer</p>
                    @if($sale->customers > 0)
                        <p class="text-white font-semibold text-lg">{{ number_format($sale->viewers / $sale->customers, 0, ',', '.') }}</p>
                    @else
                        <p class="text-white font-semibold text-lg">N/A</p>
                    @endif
                </div>
                <div>
                    <p class="text-gray-400">Viewers per Item</p>
                    @if($sale->items_sold > 0)
                        <p class="text-white font-semibold text-lg">{{ number_format($sale->viewers / $sale->items_sold, 2) }}</p>
                    @else
                        <p class="text-white font-semibold text-lg">N/A</p>
                    @endif
                </div>
                <div>
                    <p class="text-gray-400">Dibuat</p>
                    <p class="text-white font-semibold text-lg">{{ $sale->created_at->format('d M Y') }}</p>
                </div>
            </div>
        </div>

        <!-- Back Button -->
        <div class="mt-6">
            <a href="{{ route('admin.sales.index') }}" class="px-6 py-3 bg-gray-700 hover:bg-gray-600 text-white rounded-lg font-semibold transition">
                ← Kembali ke Daftar
            </a>
        </div>
    </div>
@endsection
