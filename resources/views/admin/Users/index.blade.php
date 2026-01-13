@extends('admin.layout', ['title' => 'User Management'])

@section('content')
<div class="bg-gray-800 rounded-lg shadow-lg p-6 mb-6">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold text-white">User Management</h1>
        <a href="{{ route('admin.dashboard') }}" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded transition font-semibold">
            Back to Dashboard
        </a>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-4 gap-6">
        <!-- Create User Form -->
        <div class="lg:col-span-1">
            <div class="bg-gray-700 rounded-lg p-6">
                <h3 class="text-xl font-bold text-white mb-4">Create New User</h3>
                <form action="{{ route('admin.users.store') }}" method="POST" class="space-y-4">
                    @csrf
                    <div>
                        <label class="block text-gray-300 font-semibold mb-2 text-sm">Full Name</label>
                        <input type="text" name="name" class="w-full px-3 py-2 bg-gray-600 text-white border border-gray-500 rounded focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                    </div>
                    <div>
                        <label class="block text-gray-300 font-semibold mb-2 text-sm">Email</label>
                        <input type="email" name="email" class="w-full px-3 py-2 bg-gray-600 text-white border border-gray-500 rounded focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                    </div>
                    <div>
                        <label class="block text-gray-300 font-semibold mb-2 text-sm">Password</label>
                        <input type="password" name="password" class="w-full px-3 py-2 bg-gray-600 text-white border border-gray-500 rounded focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                    </div>
                    <div>
                        <label class="block text-gray-300 font-semibold mb-2 text-sm">Role</label>
                        <select name="role" class="w-full px-3 py-2 bg-gray-600 text-white border border-gray-500 rounded focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <option value="user">User</option>
                            <option value="admin">Admin</option>
                        </select>
                    </div>
                    <button type="submit" class="w-full px-4 py-2 bg-green-600 hover:bg-green-700 text-white rounded font-semibold transition">
                        Create User
                    </button>
                </form>
            </div>
        </div>

        <!-- User List Table -->
        <div class="lg:col-span-3">
            <div class="bg-gray-700 rounded-lg overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-gray-300">
                        <thead class="bg-gray-800 text-white">
                            <tr>
                                <th class="px-6 py-4 text-left font-semibold text-base">Name</th>
                                <th class="px-6 py-4 text-left font-semibold text-base">Email</th>
                                <th class="px-6 py-4 text-left font-semibold text-base">Role</th>
                                <th class="px-6 py-4 text-left font-semibold text-base">Files</th>
                                <th class="px-6 py-4 text-center font-semibold text-base">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-600">
                            @forelse($users as $user)
                            <tr class="hover:bg-gray-600 transition">
                                <td class="px-6 py-4">
                                    <p class="font-semibold text-white text-base">{{ $user->name }}</p>
                                </td>
                                <td class="px-6 py-4 text-base">{{ $user->email }}</td>
                                <td class="px-6 py-4">
                                    <span class="px-3 py-1 rounded-full text-sm font-semibold {{ $user->role === 'admin' ? 'bg-red-600 text-white' : 'bg-blue-600 text-white' }}">
                                        {{ $user->role }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-base">
                                    <span class="text-gray-300">{{ $user->uploadedFiles->count() }}</span>
                                </td>
                                <td class="px-6 py-4 text-center space-x-2">
                                    <a href="{{ route('admin.users.show', $user->id) }}" class="px-3 py-1 bg-blue-600 hover:bg-blue-700 text-white rounded text-sm transition font-semibold">
                                        View
                                    </a>
                                    <a href="{{ route('admin.users.edit', $user->id) }}" class="px-3 py-1 bg-yellow-600 hover:bg-yellow-700 text-white rounded text-sm transition font-semibold">
                                        Edit
                                    </a>
                                    @if($user->id !== auth()->id())
                                    <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST" onsubmit="return confirm('Are you sure?')" class="inline-block">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="px-3 py-1 bg-red-600 hover:bg-red-700 text-white rounded text-sm transition font-semibold">
                                            Delete
                                        </button>
                                    </form>
                                    @endif
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="px-6 py-8 text-center text-gray-400 text-base">
                                    No users found
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection