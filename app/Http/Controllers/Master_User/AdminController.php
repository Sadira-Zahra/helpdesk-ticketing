<?php

namespace App\Http\Controllers\Master_User;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Departemen;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class AdminController extends Controller
{
    public function index()
    {
        $users = User::with('departemen')
            ->where('role', 'admin')
            ->whereHas('departemen', function($query) {
                $query->whereIn('nama_departemen', ['IT', 'GA', 'EHS']);
            })
            ->orderBy('created_at', 'desc')
            ->get();
        
        $departemens = Departemen::whereIn('nama_departemen', ['IT', 'GA', 'EHS'])->get();
        
        return view('master_user.admin', compact('users', 'departemens'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nik' => 'required|unique:users,nik',
            'username' => 'required|unique:users,username|min:4',
            'nama' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6',
            'no_telp' => 'nullable|string|max:20',
            'departemen_id' => 'required|exists:departemen,id',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        // Validasi departemen hanya IT, GA, EHS
        $departemen = Departemen::findOrFail($request->departemen_id);
        if (!in_array($departemen->nama_departemen, ['IT', 'GA', 'EHS'])) {
            return back()->with('error', 'Admin hanya bisa dari departemen IT, GA, atau EHS');
        }

        $data = $request->except('photo', 'password');
        $data['password'] = Hash::make($request->password);
        $data['role'] = 'admin';

        if ($request->hasFile('photo')) {
            $data['photo'] = $request->file('photo')->store('users', 'public');
        }

        User::create($data);

        return redirect()->route('master_user.admin.index')
            ->with('success', 'Admin berhasil ditambahkan');
    }

    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $request->validate([
            'nik' => 'required|unique:users,nik,' . $id,
            'username' => 'required|unique:users,username,' . $id . '|min:4',
            'nama' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $id,
            'password' => 'nullable|min:6',
            'no_telp' => 'nullable|string|max:20',
            'departemen_id' => 'required|exists:departemen,id',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        // Validasi departemen hanya IT, GA, EHS
        $departemen = Departemen::findOrFail($request->departemen_id);
        if (!in_array($departemen->nama_departemen, ['IT', 'GA', 'EHS'])) {
            return back()->with('error', 'Admin hanya bisa dari departemen IT, GA, atau EHS');
        }

        $data = $request->except('photo', 'password');

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        if ($request->hasFile('photo')) {
            if ($user->photo) {
                Storage::disk('public')->delete($user->photo);
            }
            $data['photo'] = $request->file('photo')->store('users', 'public');
        }

        $user->update($data);

        return redirect()->route('master_user.admin.index')
            ->with('success', 'Admin berhasil diupdate');
    }

    public function destroy($id)
    {
        $user = User::findOrFail($id);
        
        if ($user->photo) {
            Storage::disk('public')->delete($user->photo);
        }

        $user->delete();

        return redirect()->route('master_user.admin.index')
            ->with('success', 'Admin berhasil dihapus');
    }
}
