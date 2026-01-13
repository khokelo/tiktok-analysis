@extends('admin.layout')

@section('content')
    <a href="{{ route('admin.files.index') }}" class="text-indigo-400 hover:text-indigo-300 mb-6 inline-block">← Kembali</a>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- File Info -->
        <div class="lg:col-span-2">
            <div class="bg-gray-800 rounded-lg shadow-lg border border-gray-700 p-8 mb-6">
                <h2 class="text-2xl font-bold text-white mb-6">Detail File</h2>
                
                <div class="space-y-4">
                    <div class="flex justify-between items-center py-3 border-b border-gray-700">
                        <span class="text-gray-400">Nama File:</span>
                        <span class="text-white font-semibold">{{ $file->original_name }}</span>
                    </div>
                    <div class="flex justify-between items-center py-3 border-b border-gray-700">
                        <span class="text-gray-400">File Path:</span>
                        <span class="text-gray-300 text-sm">{{ $file->file_name }}</span>
                    </div>
                    <div class="flex justify-between items-center py-3 border-b border-gray-700">
                        <span class="text-gray-400">Di-upload oleh:</span>
                        <span class="text-white font-semibold">{{ $file->user->name ?? 'Unknown' }}</span>
                    </div>
                    <div class="flex justify-between items-center py-3 border-b border-gray-700">
                        <span class="text-gray-400">Ukuran:</span>
                        <span class="text-white font-semibold">{{ number_format($file->file_size / 1024, 2) }} KB</span>
                    </div>
                    <div class="flex justify-between items-center py-3 border-b border-gray-700">
                        <span class="text-gray-400">Jumlah Baris:</span>
                        <span class="text-white font-semibold">{{ $file->row_count }}</span>
                    </div>
                    <div class="flex justify-between items-center py-3 border-b border-gray-700">
                        <span class="text-gray-400">Status:</span>
                        <span class="px-3 py-1 rounded-full text-xs font-semibold
                            @if($file->status === 'processed') bg-green-900 text-green-200
                            @elseif($file->status === 'failed') bg-red-900 text-red-200
                            @else bg-yellow-900 text-yellow-200
                            @endif">
                            {{ ucfirst($file->status) }}
                        </span>
                    </div>
                    <div class="flex justify-between items-center py-3">
                        <span class="text-gray-400">Tanggal Upload:</span>
                        <span class="text-white font-semibold">{{ $file->created_at->format('d M Y H:i:s') }}</span>
                    </div>
                </div>

                <div class="flex gap-4 mt-8 pt-4 border-t border-gray-700">
                    <a href="{{ route('admin.files.download', $file) }}" class="flex-1 px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg transition text-center">
                        Download File
                    </a>
                    <button onclick="deleteFile({{ $file->id }})" class="flex-1 px-6 py-3 bg-red-600 hover:bg-red-700 text-white font-medium rounded-lg transition">
                        Hapus File
                    </button>
                </div>
            </div>
        </div>

        <!-- Statistics -->
        <div>
            <div class="bg-gray-800 rounded-lg shadow-lg border border-gray-700 p-6 mb-6">
                <h3 class="text-lg font-bold text-white mb-4">📊 Statistik</h3>
                <div class="space-y-4">
                    <div class="bg-gray-700 rounded-lg p-4">
                        <p class="text-gray-400 text-sm">Rows Uploaded</p>
                        <p class="text-3xl font-bold text-indigo-400">{{ $file->row_count }}</p>
                    </div>
                    <div class="bg-gray-700 rounded-lg p-4">
                        <p class="text-gray-400 text-sm">File Size</p>
                        <p class="text-2xl font-bold text-green-400">{{ number_format($file->file_size / 1024, 2) }} KB</p>
                    </div>
                </div>
            </div>

            <div class="bg-gray-800 rounded-lg shadow-lg border border-gray-700 p-6">
                <h3 class="text-lg font-bold text-white mb-4">👤 Uploader Info</h3>
                <div class="space-y-3">
                    <div>
                        <p class="text-gray-400 text-sm">Nama</p>
                        <p class="text-white font-semibold">{{ $file->user->name ?? 'Unknown' }}</p>
                    </div>
                    <div>
                        <p class="text-gray-400 text-sm">Email</p>
                        <p class="text-white font-semibold">{{ $file->user->email ?? 'Unknown' }}</p>
                    </div>
                    <div>
                        <p class="text-gray-400 text-sm">Total Upload</p>
                        <p class="text-white font-semibold">{{ $file->user->uploadedFiles()->count() ?? 0 }} Files</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Sales Data (Preview) -->
    <div class="mt-8 bg-gray-800 rounded-lg shadow-lg border border-gray-700 p-6">
        <h3 class="text-lg font-bold text-white mb-4">📋 Preview Data Sales</h3>
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-gray-700">
                    <tr>
                        <th class="px-4 py-3 text-left text-gray-300">Campaign</th>
                        <th class="px-4 py-3 text-left text-gray-300">Date</th>
                        <th class="px-4 py-3 text-left text-gray-300">Direct GMV</th>
                        <th class="px-4 py-3 text-left text-gray-300">Items Sold</th>
                        <th class="px-4 py-3 text-left text-gray-300">Customers</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-700">
                    @forelse($relatedSales->take(10) as $sale)
                        <tr class="hover:bg-gray-700 transition">
                            <td class="px-4 py-3 text-white">{{ $sale->campaign }}</td>
                            <td class="px-4 py-3 text-gray-300">{{ $sale->date }}</td>
                            <td class="px-4 py-3 text-green-400">Rp {{ number_format($sale->direct_gmv, 0, ',', '.') }}</td>
                            <td class="px-4 py-3 text-blue-400">{{ $sale->items_sold }}</td>
                            <td class="px-4 py-3 text-purple-400">{{ $sale->customers }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-4 py-8 text-center text-gray-400">Belum ada data sales</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <script>
        function deleteFile(id) {
            if (confirm('Apakah Anda yakin ingin menghapus file ini? Aksi ini tidak dapat dibatalkan.')) {
                fetch(`/admin/files/${id}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '{{ csrf_token() }}'
                    }
                }).then(response => {
                    if (response.ok) {
                        window.location.href = '{{ route('admin.files.index') }}';
                    }
                });
            }
        }
    </script>
@endsection
