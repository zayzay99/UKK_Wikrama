<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function showStudentAuth()
    {
        return view('auth.login');
    }

    public function showAdminAuth()
    {
        return view('auth.admin');
    }

    public function studentLogin(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::attempt([...$credentials, 'role' => 'siswa'])) {
            $request->session()->regenerate();

            return redirect()->route('siswa.history')->with('success', 'Login berhasil. Selamat datang di portal siswa.');
        }

        return back()->withErrors(['email' => 'Email atau password salah.']);
    }

    public function studentRegister(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'nis' => ['required', 'string', 'max:255', 'unique:users,nis'],
            'rayon' => ['required', 'string', 'max:255'],
            'rombel' => ['required', 'string', 'max:255'],
        ]);

        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'role' => 'siswa',
            'nis' => $data['nis'],
            'staff_id' => null,
            'rayon' => $data['rayon'],
            'rombel' => $data['rombel'],
        ]);

        Auth::login($user);
        $request->session()->regenerate();

        return redirect()->route('siswa.history')->with('success', 'Registrasi siswa berhasil. Selamat datang.');
    }

    public function petugasLogin(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::attempt([...$credentials, 'role' => 'petugas'])) {
            $request->session()->regenerate();

            return redirect()->route('transactions.index')->with('success', 'Login berhasil. Selamat bekerja, Petugas.');
        }

        return back()->withErrors(['email' => 'Email atau password salah.'])->onlyInput('email');
    }

    public function adminLogin(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::attempt([...$credentials, 'role' => 'admin'])) {
            $request->session()->regenerate();

            return redirect()->route('reports.index')->with('success', 'Login berhasil. Selamat datang, Admin.');
        }

        return back()->withErrors(['email' => 'Email atau password salah.'])->onlyInput('email');
    }

    public function adminRegister(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'staff_id' => ['required', 'string', 'max:255', 'unique:users,staff_id'],
        ]);

        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'role' => 'admin',
            'nis' => null,
            'staff_id' => $data['staff_id'],
            'rayon' => null,
            'rombel' => null,
        ]);

        Auth::login($user);
        $request->session()->regenerate();

        return redirect()->route('reports.index')->with('success', 'Registrasi admin berhasil. Selamat datang.');
    }

    public function logout(Request $request)
    {
        $role = Auth::user()?->role;

        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        $redirectRoute = match($role) {
            'siswa', 'petugas' => 'login',
            'admin' => 'admin.login',
            default => 'login'
        };

        return redirect()->route($redirectRoute)->with('success', 'Logout berhasil. Sampai jumpa.');
    }
}
