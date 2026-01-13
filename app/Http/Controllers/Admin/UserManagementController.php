<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserManagementController extends Controller
{
    /**
     * Display list of users
     */
    public function index()
    {
        $users = User::with('uploadedFiles')
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return view('admin.users.index', compact('users'));
    }

    /**
     * Show user details
     */
    public function show(User $user)
    {
        $user->load('uploadedFiles');

        return view('admin.users.show', compact('user'));
    }

    /**
     * Show create user form
     */
    public function create()
    {
        return view('admin.users.create');
    }

    /**
     * Store new user
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
            'role' => 'required|in:user,admin',
        ]);

        $validated['password'] = Hash::make($validated['password']);

        User::create($validated);

        return redirect()->route('admin.users.index')
            ->with('success', 'User berhasil ditambahkan.');
    }

    /**
     * Show edit user form
     */
    public function edit(User $user)
    {
        return view('admin.users.edit', compact('user'));
    }

    /**
     * Update user
     */
    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,'.$user->id,
            'role' => 'required|in:user,admin',
            'password' => 'nullable|string|min:8|confirmed',
        ]);

        if (! empty($validated['password'])) {
            $validated['password'] = Hash::make($validated['password']);
        } else {
            unset($validated['password']);
        }

        $user->update($validated);

        return redirect()->route('admin.users.index')
            ->with('success', 'User berhasil diperbarui.');
    }

    /**
     * Delete user
     */
    public function destroy(User $user)
    {
        try {
            // Check if user is the current user
            if ($user->id === auth()->id()) {
                return redirect()->route('admin.users.index')
                    ->with('error', 'Tidak dapat menghapus user yang sedang login.');
            }

            $user->delete();

            return redirect()->route('admin.users.index')
                ->with('success', 'User berhasil dihapus.');
        } catch (\Exception $e) {
            return redirect()->route('admin.users.index')
                ->with('error', 'Gagal menghapus user: '.$e->getMessage());
        }
    }

    /**
     * Update user role
     */
    public function updateRole(Request $request, User $user)
    {
        $validated = $request->validate([
            'role' => 'required|in:user,admin',
        ]);

        if ($user->id === auth()->id() && $validated['role'] === 'user') {
            return redirect()->back()
                ->with('error', 'Tidak dapat mengubah role user yang sedang login.');
        }

        $user->update($validated);

        return redirect()->back()
            ->with('success', 'Role user berhasil diperbarui.');
    }
}
