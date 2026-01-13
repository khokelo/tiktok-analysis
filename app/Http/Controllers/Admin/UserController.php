<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index()
    {
        $users = User::where('id', '!=', auth()->id())->get(); // Tampilkan user lain kecuali diri sendiri

        return view('admin.users.index', compact('users'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|unique:users',
            'password' => 'required|min:8',
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 0, // Default sebagai user biasa
        ]);

        return back()->with('success', 'User baru berhasil didaftarkan!');
    }

    public function destroy(User $user)
    {
        $user->delete();

        return back()->with('success', 'User berhasil dihapus.');
    }
}
