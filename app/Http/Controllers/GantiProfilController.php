<?php

namespace App\Http\Controllers;

use App\Models\Departemen;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class GantiProfilController extends Controller
{
    /**
     * Display user profile
     */
    public function index()
    {
        $user = Auth::user();
        $user->load('departemen');
        
        $departemens = Departemen::orderBy('nama_departemen')->get();
        
        return view('profil.ganti_profil', compact('user', 'departemens'));
    }

    /**
     * Update user profile
     */
    public function update(Request $request)
    {
        $user = Auth::user();
        
        try {
            // Validation rules
            $rules = [
                'nik' => ['required', 'string', 'max:20', Rule::unique('users', 'nik')->ignore($user->id)],
                'username' => ['required', 'string', 'max:50', Rule::unique('users', 'username')->ignore($user->id)],
                'nama' => 'required|string|max:255',
                'email' => ['required', 'email', Rule::unique('users', 'email')->ignore($user->id)],
                'no_telepon' => 'nullable|string|max:20',
                'photo' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            ];

            // Add departemen validation for non-administrator
            if ($user->role !== 'administrator') {
                $rules['departemen_id'] = 'required|exists:departemen,id';
            }

            $validated = $request->validate($rules, [
                'nik.required' => 'NIK harus diisi',
                'nik.unique' => 'NIK sudah digunakan',
                'username.required' => 'Username harus diisi',
                'username.unique' => 'Username sudah digunakan',
                'nama.required' => 'Nama lengkap harus diisi',
                'email.required' => 'Email harus diisi',
                'email.email' => 'Format email tidak valid',
                'email.unique' => 'Email sudah digunakan',
                'photo.image' => 'File harus berupa gambar',
                'photo.mimes' => 'Format gambar harus jpeg, png, atau jpg',
                'photo.max' => 'Ukuran gambar maksimal 2MB',
                'departemen_id.required' => 'Departemen harus dipilih',
            ]);

            // Set departemen_id null for administrator
            if ($user->role === 'administrator') {
                $validated['departemen_id'] = null;
            }

            // Handle photo upload
            if ($request->hasFile('photo')) {
                // Delete old photo
                if ($user->photo && Storage::disk('public')->exists($user->photo)) {
                    Storage::disk('public')->delete($user->photo);
                }

                $validated['photo'] = $request->file('photo')->store('users', 'public');
            }

            // Update user
            $user->update($validated);

            return redirect()->route('ganti_profil.index')
                ->with('success', 'Profil berhasil diperbarui!');

        } catch (\Illuminate\Validation\ValidationException $e) {
            return redirect()->back()
                ->withErrors($e->errors())
                ->withInput();
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Update password
     */
    public function updatePassword(Request $request)
    {
        $user = Auth::user();
        
        try {
            $validated = $request->validate([
                'current_password' => 'required|string',
                'new_password' => 'required|string|min:6|confirmed',
            ], [
                'current_password.required' => 'Password lama harus diisi',
                'new_password.required' => 'Password baru harus diisi',
                'new_password.min' => 'Password baru minimal 6 karakter',
                'new_password.confirmed' => 'Konfirmasi password tidak cocok',
            ]);

            // Check current password
            if (!Hash::check($validated['current_password'], $user->password)) {
                return redirect()->back()
                    ->with('error_password', 'Password lama tidak sesuai!');
            }

            // Update password
            $user->update([
                'password' => Hash::make($validated['new_password'])
            ]);

            return redirect()->route('ganti_profil.index')
                ->with('success', 'Password berhasil diubah!');

        } catch (\Illuminate\Validation\ValidationException $e) {
            return redirect()->back()
                ->withErrors($e->errors())
                ->with('error_password', 'Validasi password gagal');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error_password', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
}
