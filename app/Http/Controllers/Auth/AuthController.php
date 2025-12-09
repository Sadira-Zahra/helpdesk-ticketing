<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use Illuminate\View\View;
use App\Models\Departemen;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\RedirectResponse;

class AuthController extends Controller
{
    
    /**
     * Show login user page
     */
    public function showLoginUser(): View
    {
        return view('auth.login_user');
    }

    /**
     * Show register user page
     */
    public function showRegisterUser(): View
    {
        // Ambil semua departemen untuk dropdown
        $departemens = Departemen::orderBy('nama_departemen', 'asc')->get();
        
        return view('auth.register_user', compact('departemens'));
    }

    /**
     * Show login petugas page
     */
    public function showLoginPetugas(): View
    {
        return view('auth.login_petugas');
    }

    /**
     * Handle login user
     */
    public function loginUserPost(Request $request): RedirectResponse
    {
        $request->validate([
            'username' => 'required|string',
            'password' => 'required|string|min:6',
        ], [
            'username.required' => 'Username harus diisi',
            'password.required' => 'Password harus diisi',
            'password.min' => 'Password minimal 6 karakter',
        ]);

        // Check user exists and verify password
        $user = User::where('username', $request->username)
                    ->where('role', 'user')
                    ->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return redirect()->route('login_user')
                ->withErrors(['error' => 'Username atau password salah!'])
                ->withInput($request->only('username'));
        }

        Auth::login($user);
        
        $request->session()->regenerate();
        
        return redirect()->intended('dashboard')
            ->with('success', 'Selamat datang, ' . $user->nama . '!');
    }

    /**
     * Handle register user
     */
    public function registerUserPost(Request $request): RedirectResponse
    {
        $request->validate([
            'nik' => 'required|string|unique:users,nik',
            'username' => 'required|string|unique:users,username|max:50',
            'nama' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'no_telepon' => 'nullable|string|max:20',
            'departemen_id' => 'required|exists:departemens,id',
            'password' => 'required|string|min:6|confirmed',
        ], [
            'nik.required' => 'NIK harus diisi',
            'nik.unique' => 'NIK sudah terdaftar',
            'username.required' => 'Username harus diisi',
            'username.unique' => 'Username sudah terdaftar',
            'nama.required' => 'Nama harus diisi',
            'email.required' => 'Email harus diisi',
            'email.email' => 'Format email tidak valid',
            'email.unique' => 'Email sudah terdaftar',
            'departemen_id.required' => 'Departemen harus dipilih',
            'password.required' => 'Password harus diisi',
            'password.min' => 'Password minimal 6 karakter',
            'password.confirmed' => 'Konfirmasi password tidak cocok',
        ]);

        $user = User::create([
            'nik' => $request->nik,
            'username' => $request->username,
            'nama' => $request->nama,
            'email' => $request->email,
            'no_telepon' => $request->no_telepon,
            'departemen_id' => $request->departemen_id,
            'password' => Hash::make($request->password),
            'role' => 'user',
        ]);

        Auth::login($user);
        
        $request->session()->regenerate();
        
        return redirect()->intended('dashboard')
            ->with('success', 'Pendaftaran berhasil! Selamat datang, ' . $user->nama . '!');
    }

    /**
     * Handle login petugas (admin, administrator, teknisi)
     */
    public function loginPetugasPost(Request $request): RedirectResponse
    {
        $request->validate([
            'username' => 'required|string',
            'password' => 'required|string|min:6',
        ], [
            'username.required' => 'Username harus diisi',
            'password.required' => 'Password harus diisi',
            'password.min' => 'Password minimal 6 karakter',
        ]);

        // Check petugas exists with valid roles
        $user = User::where('username', $request->username)
            ->whereIn('role', ['admin', 'administrator', 'teknisi'])
            ->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return redirect()->route('login_petugas')
                ->withErrors(['error' => 'Username atau password salah!'])
                ->withInput($request->only('username'));
        }

        Auth::login($user);
        
        $request->session()->regenerate();
        
        return redirect()->intended('dashboard')
            ->with('success', 'Selamat datang, ' . $user->nama . '!');
    }

    /**
     * Handle logout
     */
    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();
        
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        
        return redirect('/')
            ->with('success', 'Berhasil logout!');
    }
}
