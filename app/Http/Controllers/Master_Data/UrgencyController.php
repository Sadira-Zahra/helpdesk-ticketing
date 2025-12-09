<?php

namespace App\Http\Controllers\Master_Data;

use App\Http\Controllers\Controller;
use App\Models\Urgency;
use Illuminate\Http\Request;

class UrgencyController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $urgencies = Urgency::withCount('tiket')
            ->orderBy('jam', 'asc')
            ->get();
        
        return view('master_data.urgency', compact('urgencies'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'urgency' => 'required|string|max:255|unique:urgency,urgency',
            'jam' => 'required|integer|min:1|max:720',
        ], [
            'urgency.required' => 'Nama urgency wajib diisi',
            'urgency.unique' => 'Nama urgency sudah ada',
            'urgency.max' => 'Nama urgency maksimal 255 karakter',
            'jam.required' => 'Durasi jam wajib diisi',
            'jam.integer' => 'Durasi jam harus berupa angka',
            'jam.min' => 'Durasi jam minimal 1 jam',
            'jam.max' => 'Durasi jam maksimal 720 jam (30 hari)',
        ]);

        Urgency::create([
            'urgency' => $request->urgency,
            'jam' => $request->jam,
        ]);

        return redirect()->route('urgency.index')
            ->with('success', 'Urgency berhasil ditambahkan');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $urgency = Urgency::findOrFail($id);

        $request->validate([
            'urgency' => 'required|string|max:255|unique:urgency,urgency,' . $id,
            'jam' => 'required|integer|min:1|max:720',
        ], [
            'urgency.required' => 'Nama urgency wajib diisi',
            'urgency.unique' => 'Nama urgency sudah ada',
            'urgency.max' => 'Nama urgency maksimal 255 karakter',
            'jam.required' => 'Durasi jam wajib diisi',
            'jam.integer' => 'Durasi jam harus berupa angka',
            'jam.min' => 'Durasi jam minimal 1 jam',
            'jam.max' => 'Durasi jam maksimal 720 jam (30 hari)',
        ]);

        $urgency->update([
            'urgency' => $request->urgency,
            'jam' => $request->jam,
        ]);

        return redirect()->route('urgency.index')
            ->with('success', 'Urgency berhasil diupdate');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $urgency = Urgency::findOrFail($id);
        
        // Cek apakah urgency masih digunakan
        if ($urgency->tiket()->count() > 0) {
            return redirect()->route('urgency.index')
                ->with('error', 'Urgency tidak dapat dihapus karena masih digunakan oleh ' . $urgency->tiket()->count() . ' tiket');
        }

        $urgency->delete();

        return redirect()->route('urgency.index')
            ->with('success', 'Urgency berhasil dihapus');
    }
}
