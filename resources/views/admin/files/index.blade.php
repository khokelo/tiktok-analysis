@extends('admin.layout')

@section('content')
    <div class="mb-6">
        <h3 class="text-xl font-bold text-white mb-4">Daftar File CSV</h3>
        
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
            <div class="bg-gray-700 rounded-lg p-4">
                <p class="text-gray-400 text-sm">Total File</p>
                <p class="text-2xl font-bold text-white">{{ $files->total() }}</p>
            </div>
            <div class="bg-gray-700 rounded-lg p-4">
                <p class="text-gray-400 text-sm">Processed</p>
                <p class="text-2xl font-bold text-green-400">{{ $files->where('status', 'processed')->count() }}</p>
            </div>
            <div class="bg-gray-700 rounded-lg p-4">
                <p class="text-gray-400 text-sm">Pending</p>
                <p class="text-2xl font-bold text-yellow-400">{{ $files->where('status', 'pending')->count() }}</p>
            </div>
            <div class="bg-gray-700 rounded-lg p-4">
                <p class="text-gray-400 text-sm">Failed</p>
                <p class="text-2xl font-bold text-red-400">{{ $files->where('status', 'failed')->count() }}</p>
            </div>
        </div>
    </div>

    <div class="bg-gray-800 rounded-lg shadow-lg border border-gray-700 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-gray-700">
                    <tr>
                        <th class="px-6 py-4 text-left text-gray-300 font-semibold">
                            <input type="checkbox" id="selectAll" class="rounded">
                        </th>
                        <th class="px-6 py-4 text-left text-gray-300 font-semibold">Nama File</th>
                        <th class="px-6 py-4 text-left text-gray-300 font-semibold">User</th>
                        <th class="px-6 py-4 text-left text-gray-300 font-semibold">Ukuran</th>
                        <th class="px-6 py-4 text-left text-gray-300 font-semibold">Rows</th>
                        <th class="px-6 py-4 text-left text-gray-300 font-semibold">Status</th>
                        <th class="px-6 py-4 text-left text-gray-300 font-semibold">Tanggal Upload</th>
                        <th class="px-6 py-4 text-left text-gray-300 font-semibold">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-700">
                    @forelse($files as $file)
                        <tr class="hover:bg-gray-700 transition">
                            <td class="px-6 py-4">
                                <input type="checkbox" value="{{ $file->id }}" class="file-checkbox rounded">
                            </td>
                            <td class="px-6 py-4">
                                <p class="text-white font-medium">{{ $file->original_name }}</p>
                                <p class="text-gray-400 text-xs">{{ $file->file_name }}</p>
                            </td>
                            <td class="px-6 py-4 text-gray-300">{{ $file->user->name ?? 'Unknown' }}</td>
                            <td class="px-6 py-4 text-gray-300">{{ number_format($file->file_size / 1024, 2) }} KB</td>
                            <td class="px-6 py-4 text-gray-300">{{ $file->row_count }}</td>
                            <td class="px-6 py-4">
                                <select 
                                    class="px-3 py-1 rounded text-xs font-semibold 
                                        @if($file->status === 'processed') bg-green-900 text-green-200
                                        @elseif($file->status === 'failed') bg-red-900 text-red-200
                                        @else bg-yellow-900 text-yellow-200
                                        @endif"
                                    onchange="updateFileStatus({{ $file->id }}, this.value)"
                                >
                                    <option value="pending" {{ $file->status === 'pending' ? 'selected' : '' }}>Pending</option>
                                    <option value="processed" {{ $file->status === 'processed' ? 'selected' : '' }}>Processed</option>
                                    <option value="failed" {{ $file->status === 'failed' ? 'selected' : '' }}>Failed</option>
                                </select>
                            </td>
                            <td class="px-6 py-4 text-gray-400 text-xs">{{ $file->created_at->format('d M Y H:i') }}</td>
                            <td class="px-6 py-4">
                                <div class="flex gap-2">
                                    <a href="{{ route('admin.files.download', $file) }}" class="text-blue-400 hover:text-blue-300 transition">Download</a>
                                    <a href="{{ route('admin.files.show', $file) }}" class="text-indigo-400 hover:text-indigo-300 transition">View</a>
                                    <button onclick="deleteFile({{ $file->id }})" class="text-red-400 hover:text-red-300 transition">Hapus</button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="px-6 py-8 text-center text-gray-400">Belum ada file</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($files->count() > 0)
            <div class="bg-gray-700 px-6 py-4 flex justify-between items-center">
                <button 
                    onclick="bulkDelete()" 
                    class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded-lg transition text-sm"
                    id="bulkDeleteBtn"
                    style="display: none;"
                >
                    Hapus Terpilih
                </button>
                <div id="selectedCount" class="text-gray-300"></div>
            </div>
        @endif
    </div>

    <div class="mt-6">
        {{ $files->links() }}
    </div>

    <script>
        const fileCheckboxes = document.querySelectorAll('.file-checkbox');
        const selectAllCheckbox = document.getElementById('selectAll');
        const bulkDeleteBtn = document.getElementById('bulkDeleteBtn');
        const selectedCount = document.getElementById('selectedCount');

        function updateSelectedCount() {
            const count = Array.from(fileCheckboxes).filter(cb => cb.checked).length;
            if (count > 0) {
                bulkDeleteBtn.style.display = 'block';
                selectedCount.textContent = `${count} file dipilih`;
            } else {
                bulkDeleteBtn.style.display = 'none';
                selectedCount.textContent = '';
            }
        }

        selectAllCheckbox.addEventListener('change', function() {
            fileCheckboxes.forEach(cb => cb.checked = this.checked);
            updateSelectedCount();
        });

        fileCheckboxes.forEach(cb => {
            cb.addEventListener('change', updateSelectedCount);
        });

        function deleteFile(id) {
            if (confirm('Apakah Anda yakin ingin menghapus file ini?')) {
                fetch(`/admin/files/${id}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '{{ csrf_token() }}'
                    }
                }).then(response => {
                    if (response.ok) {
                        window.location.reload();
                    }
                });
            }
        }

        function bulkDelete() {
            const selected = Array.from(fileCheckboxes)
                .filter(cb => cb.checked)
                .map(cb => cb.value);

            if (selected.length === 0) {
                alert('Pilih file terlebih dahulu');
                return;
            }

            if (confirm(`Apakah Anda yakin ingin menghapus ${selected.length} file?`)) {
                fetch('/admin/files/bulk-delete', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '{{ csrf_token() }}',
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({ ids: selected })
                }).then(response => {
                    if (response.ok) {
                        window.location.reload();
                    }
                });
            }
        }

        function updateFileStatus(fileId, status) {
            fetch(`/admin/files/${fileId}`, {
                method: 'PUT',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '{{ csrf_token() }}',
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({ status: status })
            }).then(response => {
                if (response.ok) {
                    // Show success message
                    const msg = document.createElement('div');
                    msg.className = 'fixed top-4 right-4 p-4 bg-green-600 text-white rounded-lg';
                    msg.textContent = 'Status berhasil diperbarui';
                    document.body.appendChild(msg);
                    setTimeout(() => msg.remove(), 3000);
                }
            });
        }
    </script>
@endsection
