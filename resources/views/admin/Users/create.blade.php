@extends('admin.layout')

@section('content')
    <div class="max-w-2xl mx-auto">
        <a href="{{ route('admin.users.index') }}" class="text-indigo-400 hover:text-indigo-300 mb-6 inline-block">← Kembali</a>

        <div class="bg-gray-800 rounded-lg shadow-lg border border-gray-700 p-8">
            <h3 class="text-2xl font-bold text-white mb-6">Tambah User Baru</h3>

            <form action="{{ route('admin.users.store') }}" method="POST" class="space-y-6">
                @csrf

                <div>
                    <label for="name" class="block text-gray-300 font-medium mb-2">Nama</label>
                    <input 
                        type="text" 
                        name="name" 
                        id="name" 
                        value="{{ old('name') }}"
                        class="w-full px-4 py-2 bg-gray-700 border border-gray-600 rounded-lg text-white placeholder-gray-400 focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition"
                        required
                    >
                    @error('name')
                        <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="email" class="block text-gray-300 font-medium mb-2">Email</label>
                    <input 
                        type="email" 
                        name="email" 
                        id="email" 
                        value="{{ old('email') }}"
                        class="w-full px-4 py-2 bg-gray-700 border border-gray-600 rounded-lg text-white placeholder-gray-400 focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition"
                        required
                    >
                    @error('email')
                        <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="password" class="block text-gray-300 font-medium mb-2">Password</label>
                    <input 
                        type="password" 
                        name="password" 
                        id="password" 
                        class="w-full px-4 py-2 bg-gray-700 border border-gray-600 rounded-lg text-white placeholder-gray-400 focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition"
                        required
                    >
                    @error('password')
                        <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="password_confirmation" class="block text-gray-300 font-medium mb-2">Konfirmasi Password</label>
                    <input 
                        type="password" 
                        name="password_confirmation" 
                        id="password_confirmation" 
                        class="w-full px-4 py-2 bg-gray-700 border border-gray-600 rounded-lg text-white placeholder-gray-400 focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition"
                        required
                    >
                </div>

                <div>
                    <label for="role" class="block text-gray-300 font-medium mb-2">Role</label>
                    <select 
                        name="role" 
                        id="role"
                        class="w-full px-4 py-2 bg-gray-700 border border-gray-600 rounded-lg text-white focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition"
                        required
                    >
                        <option value="user">User</option>
                        <option value="admin">Admin</option>
                    </select>
                    @error('role')
                        <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex gap-4 pt-4">
                    <button 
                        type="submit" 
                        class="flex-1 px-6 py-3 bg-indigo-600 hover:bg-indigo-700 text-white font-medium rounded-lg transition"
                    >
                        Tambah User
                    </button>
                    <a 
                        href="{{ route('admin.users.index') }}" 
                        class="flex-1 px-6 py-3 bg-gray-700 hover:bg-gray-600 text-white font-medium rounded-lg transition text-center"
                    >
                        Batal
                    </a>
                </div>
            </form>
        </div>
    </div>
@endsection
