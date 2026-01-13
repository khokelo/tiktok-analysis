@extends('admin.layout')

@section('content')
    <!-- Alert Messages -->
    @if ($message = session('success'))
        <div class="mb-6 p-4 bg-green-900 border border-green-700 text-green-200 rounded-lg">
            {{ $message }}
        </div>
    @endif

    <!-- Header with Add Button -->
    <div class="flex justify-between items-center mb-6">
        <div>
            <h2 class="text-3xl font-bold text-white">Data Penjualan TikTok</h2>
            <p class="text-gray-400 mt-2">Kelola data penjualan dari database</p>
        </div>
        <a href="{{ route('admin.sales.create') }}" class="px-6 py-3 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg font-semibold transition">
            + Tambah Penjualan
        </a>
    </div>

    <!-- Sales Table -->
    <div class="bg-gray-800 rounded-lg p-6 shadow-lg border border-gray-700 overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="border-b border-gray-700">
                    <th class="px-4 py-4 text-left text-white font-semibold">Kampanye</th>
                    <th class="px-4 py-4 text-left text-white font-semibold">Tanggal</th>
                    <th class="px-4 py-4 text-left text-white font-semibold">Jam</th>
                    <th class="px-4 py-4 text-right text-white font-semibold">GMV (Rp)</th>
                    <th class="px-4 py-4 text-right text-white font-semibold">Item Terjual</th>
                    <th class="px-4 py-4 text-right text-white font-semibold">Customer</th>
                    <th class="px-4 py-4 text-right text-white font-semibold">Viewers</th>
                    <th class="px-4 py-4 text-center text-white font-semibold">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($sales as $sale)
                    <tr class="border-b border-gray-700 hover:bg-gray-700 transition">
                        <td class="px-4 py-4 text-white font-medium">{{ $sale->campaign }}</td>
                        <td class="px-4 py-4 text-gray-300">{{ \Carbon\Carbon::parse($sale->date)->format('d M Y') }}</td>
                        <td class="px-4 py-4 text-gray-300">{{ $sale->time }}</td>
                        <td class="px-4 py-4 text-right text-green-400 font-semibold">{{ number_format($sale->direct_gmv, 0, ',', '.') }}</td>
                        <td class="px-4 py-4 text-right text-blue-400 font-semibold">{{ $sale->items_sold }}</td>
                        <td class="px-4 py-4 text-right text-purple-400 font-semibold">{{ $sale->customers }}</td>
                        <td class="px-4 py-4 text-right text-pink-400 font-semibold">{{ $sale->viewers }}</td>
                        <td class="px-4 py-4 text-center">
                            <div class="flex justify-center gap-2">
                                <a href="{{ route('admin.sales.show', $sale) }}" class="px-3 py-1 bg-blue-600 hover:bg-blue-700 text-white text-xs rounded transition font-semibold">
                                    Lihat
                                </a>
                                <a href="{{ route('admin.sales.edit', $sale) }}" class="px-3 py-1 bg-indigo-600 hover:bg-indigo-700 text-white text-xs rounded transition font-semibold">
                                    Edit
                                </a>
                                <form action="{{ route('admin.sales.destroy', $sale) }}" method="POST" class="inline" onsubmit="return confirm('Yakin ingin menghapus?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="px-3 py-1 bg-red-600 hover:bg-red-700 text-white text-xs rounded transition font-semibold">
                                        Hapus
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="px-4 py-8 text-center text-gray-400">
                            <p class="text-lg">Belum ada data penjualan</p>
                            <a href="{{ route('admin.sales.create') }}" class="text-indigo-400 hover:text-indigo-300 mt-2 inline-block">Tambah data sekarang →</a>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <!-- Pagination -->
        @if($sales->hasPages())
            <div class="mt-6 flex justify-center">
                {{ $sales->links() }}
            </div>
        @endif
    </div>

    <!-- Statistics Summary -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mt-8">
        <div class="bg-gray-800 rounded-lg p-6 border border-gray-700">
            <p class="text-gray-400 text-sm mb-2">Total Penjualan</p>
            <h3 class="text-3xl font-bold text-white">{{ $sales->total() }}</h3>
        </div>
        <div class="bg-gray-800 rounded-lg p-6 border border-gray-700">
            <p class="text-gray-400 text-sm mb-2">Total GMV</p>
            <h3 class="text-2xl font-bold text-green-400">Rp {{ number_format(\App\Models\TiktokSale::sum('direct_gmv'), 0, ',', '.') }}</h3>
        </div>
        <div class="bg-gray-800 rounded-lg p-6 border border-gray-700">
            <p class="text-gray-400 text-sm mb-2">Total Items</p>
            <h3 class="text-3xl font-bold text-blue-400">{{ number_format(\App\Models\TiktokSale::sum('items_sold'), 0, ',', '.') }}</h3>
        </div>
        <div class="bg-gray-800 rounded-lg p-6 border border-gray-700">
            <p class="text-gray-400 text-sm mb-2">Total Viewers</p>
            <h3 class="text-3xl font-bold text-pink-400">{{ number_format(\App\Models\TiktokSale::sum('viewers'), 0, ',', '.') }}</h3>
        </div>
    </div>
@endsection
