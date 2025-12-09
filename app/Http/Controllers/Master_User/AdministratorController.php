<?php

namespace App\Http\Controllers\Master_User;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class AdministratorController extends Controller
{
    public function index()
    {
        $users = User::where('role', 'administrator')
            ->whereNull('departemen_id')
            ->orderBy('created_at', 'desc')
            ->get();
        
        return view('master_user.administrator', compact('users'));
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
            'photo' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $data = $request->except('photo', 'password');
        $data['password'] = Hash::make($request->password);
        $data['role'] = 'administrator';
        $data['departemen_id'] = null;

        if ($request->hasFile('photo')) {
            $data['photo'] = $request->file('photo')->store('users', 'public');
        }

        User::create($data);

        return redirect()->route('master_user.administrator.index')
            ->with('success', 'Administrator berhasil ditambahkan');
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
            'photo' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $data = $request->except('photo', 'password');
        $data['departemen_id'] = null;

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

        return redirect()->route('master_user.administrator.index')
            ->with('success', 'Administrator berhasil diupdate');
    }

    public function destroy($id)
    {
        $user = User::findOrFail($id);
        
        if ($user->photo) {
            Storage::disk('public')->delete($user->photo);
        }

        $user->delete();

        return redirect()->route('master_user.administrator.index')
            ->with('success', 'Administrator berhasil dihapus');
    }
}
