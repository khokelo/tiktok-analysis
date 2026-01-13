@extends('admin.layout')

@section('content')
    <div class="max-w-4xl mx-auto">
        <h2 class="text-3xl font-bold text-white mb-2">Edit Penjualan</h2>
        <p class="text-gray-400 mb-6">Perbarui data penjualan TikTok</p>

        <div class="bg-gray-800 rounded-lg p-8 shadow-lg border border-gray-700">
            <form action="{{ route('admin.sales.update', $sale) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Campaign -->
                    <div>
                        <label for="campaign" class="block text-white font-semibold mb-2">Kampanye</label>
                        <input 
                            type="text" 
                            name="campaign" 
                            id="campaign"
                            value="{{ old('campaign', $sale->campaign) }}"
                            required
                            class="w-full px-4 py-3 bg-gray-700 border border-gray-600 rounded-lg text-white placeholder-gray-400 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500 transition"
                            placeholder="Nama kampanye"
                        >
                        @error('campaign')
                            <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Date -->
                    <div>
                        <label for="date" class="block text-white font-semibold mb-2">Tanggal</label>
                        <input 
                            type="date" 
                            name="date" 
                            id="date"
                            value="{{ old('date', $sale->date) }}"
                            required
                            class="w-full px-4 py-3 bg-gray-700 border border-gray-600 rounded-lg text-white focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500 transition"
                        >
                        @error('date')
                            <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Time -->
                    <div>
                        <label for="time" class="block text-white font-semibold mb-2">Jam</label>
                        <input 
                            type="time" 
                            name="time" 
                            id="time"
                            value="{{ old('time', $sale->time) }}"
                            required
                            class="w-full px-4 py-3 bg-gray-700 border border-gray-600 rounded-lg text-white focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500 transition"
                        >
                        @error('time')
                            <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Direct GMV -->
                    <div>
                        <label for="direct_gmv" class="block text-white font-semibold mb-2">Direct GMV (Rp)</label>
                        <input 
                            type="number" 
                            name="direct_gmv" 
                            id="direct_gmv"
                            value="{{ old('direct_gmv', $sale->direct_gmv) }}"
                            min="0"
                            step="0.01"
                            required
                            class="w-full px-4 py-3 bg-gray-700 border border-gray-600 rounded-lg text-white placeholder-gray-400 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500 transition"
                            placeholder="0"
                        >
                        @error('direct_gmv')
                            <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Items Sold -->
                    <div>
                        <label for="items_sold" class="block text-white font-semibold mb-2">Item Terjual</label>
                        <input 
                            type="number" 
                            name="items_sold" 
                            id="items_sold"
                            value="{{ old('items_sold', $sale->items_sold) }}"
                            min="0"
                            required
                            class="w-full px-4 py-3 bg-gray-700 border border-gray-600 rounded-lg text-white placeholder-gray-400 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500 transition"
                            placeholder="0"
                        >
                        @error('items_sold')
                            <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Customers -->
                    <div>
                        <label for="customers" class="block text-white font-semibold mb-2">Customer</label>
                        <input 
                            type="number" 
                            name="customers" 
                            id="customers"
                            value="{{ old('customers', $sale->customers) }}"
                            min="0"
                            required
                            class="w-full px-4 py-3 bg-gray-700 border border-gray-600 rounded-lg text-white placeholder-gray-400 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500 transition"
                            placeholder="0"
                        >
                        @error('customers')
                            <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Viewers -->
                    <div>
                        <label for="viewers" class="block text-white font-semibold mb-2">Viewers</label>
                        <input 
                            type="number" 
                            name="viewers" 
                            id="viewers"
                            value="{{ old('viewers', $sale->viewers) }}"
                            min="0"
                            required
                            class="w-full px-4 py-3 bg-gray-700 border border-gray-600 rounded-lg text-white placeholder-gray-400 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500 transition"
                            placeholder="0"
                        >
                        @error('viewers')
                            <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Buttons -->
                <div class="flex gap-4 mt-8">
                    <button type="submit" class="px-6 py-3 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg font-semibold transition">
                        Simpan Perubahan
                    </button>
                    <a href="{{ route('admin.sales.index') }}" class="px-6 py-3 bg-gray-700 hover:bg-gray-600 text-white rounded-lg font-semibold transition">
                        Batal
                    </a>
                </div>
            </form>
        </div>
    </div>
@endsection
