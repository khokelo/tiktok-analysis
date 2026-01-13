@extends('admin.layout')

@section('content')
    <a href="{{ route('admin.users.index') }}" class="text-indigo-400 hover:text-indigo-300 mb-6 inline-block">← Kembali</a>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- User Info -->
        <div class="lg:col-span-2">
            <div class="bg-gray-800 rounded-lg shadow-lg border border-gray-700 p-8">
                <h2 class="text-2xl font-bold text-white mb-6">Detail User</h2>
                
                <div class="space-y-4">
                    <div class="flex justify-between items-center py-3 border-b border-gray-700">
                        <span class="text-gray-400">Nama:</span>
                        <span class="text-white font-semibold">{{ $user->name }}</span>
                    </div>
                    <div class="flex justify-between items-center py-3 border-b border-gray-700">
                        <span class="text-gray-400">Email:</span>
                        <span class="text-white font-semibold">{{ $user->email }}</span>
                    </div>
                    <div class="flex justify-between items-center py-3 border-b border-gray-700">
                        <span class="text-gray-400">Role:</span>
                        <span class="px-3 py-1 rounded-full text-xs font-semibold
                            @if($user->role === 'admin') bg-purple-900 text-purple-200
                            @else bg-blue-900 text-blue-200
                            @endif">
                            {{ ucfirst($user->role) }}
                        </span>
                    </div>
                    <div class="flex justify-between items-center py-3 border-b border-gray-700">
                        <span class="text-gray-400">Total File Upload:</span>
                        <span class="text-white font-semibold">{{ $user->uploadedFiles()->count() }}</span>
                    </div>
                    <div class="flex justify-between items-center py-3">
                        <span class="text-gray-400">Member Sejak:</span>
                        <span class="text-white font-semibold">{{ $user->created_at->format('d M Y H:i') }}</span>
                    </div>
                </div>

                <div class="flex gap-4 mt-8 pt-4 border-t border-gray-700">
                    <a href="{{ route('admin.users.edit', $user) }}" class="flex-1 px-6 py-3 bg-indigo-600 hover:bg-indigo-700 text-white font-medium rounded-lg transition text-center">
                        Edit User
                    </a>
                    @if($user->id !== auth()->id())
                        <button onclick="deleteUser({{ $user->id }}, '{{ $user->name }}')" class="flex-1 px-6 py-3 bg-red-600 hover:bg-red-700 text-white font-medium rounded-lg transition">
                            Hapus User
                        </button>
                    @endif
                </div>
            </div>
        </div>

        <!-- Statistics -->
        <div>
            <div class="bg-gray-800 rounded-lg shadow-lg border border-gray-700 p-6 mb-6">
                <h3 class="text-lg font-bold text-white mb-4">📊 Upload Stats</h3>
                <div class="space-y-4">
                    <div class="bg-gray-700 rounded-lg p-4">
                        <p class="text-gray-400 text-sm">Total Files</p>
                        <p class="text-3xl font-bold text-indigo-400">{{ $user->uploadedFiles()->count() }}</p>
                    </div>
                    <div class="bg-gray-700 rounded-lg p-4">
                        <p class="text-gray-400 text-sm">Total Size</p>
                        <p class="text-2xl font-bold text-green-400">
                            {{ number_format($user->uploadedFiles()->sum('file_size') / (1024*1024), 2) }} MB
                        </p>
                    </div>
                    <div class="bg-gray-700 rounded-lg p-4">
                        <p class="text-gray-400 text-sm">Total Rows</p>
                        <p class="text-2xl font-bold text-blue-400">
                            {{ number_format($user->uploadedFiles()->sum('row_count')) }}
                        </p>
                    </div>
                </div>
            </div>

            <div class="bg-gray-800 rounded-lg shadow-lg border border-gray-700 p-6">
                <h3 class="text-lg font-bold text-white mb-4">⚡ Quick Actions</h3>
                <div class="space-y-2">
                    <a href="{{ route('admin.files.index', ['user' => $user->id]) }}" class="block w-full px-4 py-2 bg-gray-700 hover:bg-gray-600 text-white rounded-lg transition text-center text-sm">
                        Lihat Files
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Uploads Table -->
    @if($user->uploadedFiles()->count() > 0)
        <div class="mt-8 bg-gray-800 rounded-lg shadow-lg border border-gray-700 p-6">
            <h3 class="text-lg font-bold text-white mb-4">📁 Recent Uploads</h3>
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead class="bg-gray-700">
                        <tr>
                            <th class="px-4 py-3 text-left text-gray-300">Nama File</th>
                            <th class="px-4 py-3 text-left text-gray-300">Ukuran</th>
                            <th class="px-4 py-3 text-left text-gray-300">Rows</th>
                            <th class="px-4 py-3 text-left text-gray-300">Status</th>
                            <th class="px-4 py-3 text-left text-gray-300">Tanggal</th>
                            <th class="px-4 py-3 text-left text-gray-300">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-700">
                        @forelse($user->uploadedFiles()->latest()->get() as $file)
                            <tr class="hover:bg-gray-700 transition">
                                <td class="px-4 py-3 text-white">{{ $file->original_name }}</td>
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
                                <td class="px-4 py-3 text-gray-400 text-xs">{{ $file->created_at->format('d M Y') }}</td>
                                <td class="px-4 py-3">
                                    <a href="{{ route('admin.files.show', $file) }}" class="text-indigo-400 hover:text-indigo-300">View</a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-4 py-8 text-center text-gray-400">Belum ada file</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    @endif

    <script>
        function deleteUser(id, name) {
            if (confirm(`Apakah Anda yakin ingin menghapus user "${name}"? Aksi ini tidak dapat dibatalkan.`)) {
                fetch(`/admin/users/${id}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '{{ csrf_token() }}'
                    }
                }).then(response => {
                    if (response.ok) {
                        window.location.href = '{{ route('admin.users.index') }}';
                    }
                });
            }
        }
    </script>
@endsection
