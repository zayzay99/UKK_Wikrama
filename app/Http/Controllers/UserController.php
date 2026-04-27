<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $users = User::whereIn('role', ['admin', 'petugas', 'siswa'])->latest()->get();
        return view('admin.users.index', compact('users'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.users.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
            'role' => 'required|in:admin,petugas,siswa',
            'nis' => 'nullable|string|max:255|unique:users,nis',
            'staff_id' => 'nullable|string|max:255|unique:users,staff_id',
            'rayon' => 'nullable|string|max:255',
            'rombel' => 'nullable|string|max:255',
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
            'nis' => $request->role === 'siswa' ? $request->nis : null,
            'staff_id' => in_array($request->role, ['admin', 'petugas'], true) ? $request->staff_id : null,
            'rayon' => $request->role === 'siswa' ? $request->rayon : null,
            'rombel' => $request->role === 'siswa' ? $request->rombel : null,
        ]);

        return redirect()->route('users.index')->with('success', 'User berhasil ditambahkan.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $user)
    {
        return view('admin.users.edit', compact('user'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $user)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'password' => 'nullable|string|min:8',
            'role' => 'required|in:admin,petugas,siswa',
            'nis' => 'nullable|string|max:255|unique:users,nis,' . $user->id,
            'staff_id' => 'nullable|string|max:255|unique:users,staff_id,' . $user->id,
            'rayon' => 'nullable|string|max:255',
            'rombel' => 'nullable|string|max:255',
        ]);

        $user->update([
            'name' => $request->name,
            'email' => $request->email,
            'password' => $request->filled('password') ? Hash::make($request->password) : $user->password,
            'role' => $request->role,
            'nis' => $request->role === 'siswa' ? $request->nis : null,
            'staff_id' => in_array($request->role, ['admin', 'petugas'], true) ? $request->staff_id : null,
            'rayon' => $request->role === 'siswa' ? $request->rayon : null,
            'rombel' => $request->role === 'siswa' ? $request->rombel : null,
        ]);

        return redirect()->route('users.index')->with('success', 'User berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        $user->delete();
        return redirect()->route('users.index')->with('success', 'User berhasil dihapus.');
    }
}
