<?php

namespace App\Http\Controllers\Master_Data;

use App\Http\Controllers\Controller;
use App\Models\Departemen;
use Illuminate\Http\Request;

class DepartemenController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $departemens = Departemen::withCount(['users', 'tiket'])
            ->orderBy('nama_departemen', 'asc')
            ->get();
        
        return view('master_data.departemen', compact('departemens'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama_departemen' => 'required|string|max:255|unique:departemen,nama_departemen',
        ], [
            'nama_departemen.required' => 'Nama departemen wajib diisi',
            'nama_departemen.unique' => 'Nama departemen sudah ada',
            'nama_departemen.max' => 'Nama departemen maksimal 255 karakter',
        ]);

        Departemen::create([
            'nama_departemen' => $request->nama_departemen,
        ]);

        return redirect()->route('departemen.index')
            ->with('success', 'Departemen berhasil ditambahkan');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $departemen = Departemen::findOrFail($id);

        $request->validate([
            'nama_departemen' => 'required|string|max:255|unique:departemen,nama_departemen,' . $id,
        ], [
            'nama_departemen.required' => 'Nama departemen wajib diisi',
            'nama_departemen.unique' => 'Nama departemen sudah ada',
            'nama_departemen.max' => 'Nama departemen maksimal 255 karakter',
        ]);

        $departemen->update([
            'nama_departemen' => $request->nama_departemen,
        ]);

        return redirect()->route('departemen.index')
            ->with('success', 'Departemen berhasil diupdate');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $departemen = Departemen::findOrFail($id);
        
        // Cek apakah departemen masih digunakan
        if ($departemen->users()->count() > 0) {
            return redirect()->route('departemen.index')
                ->with('error', 'Departemen tidak dapat dihapus karena masih digunakan oleh ' . $departemen->users()->count() . ' user');
        }
        
        if ($departemen->tiket()->count() > 0) {
            return redirect()->route('departemen.index')
                ->with('error', 'Departemen tidak dapat dihapus karena masih memiliki ' . $departemen->tiket()->count() . ' tiket');
        }

        $departemen->delete();

        return redirect()->route('departemen.index')
            ->with('success', 'Departemen berhasil dihapus');
    }
}
