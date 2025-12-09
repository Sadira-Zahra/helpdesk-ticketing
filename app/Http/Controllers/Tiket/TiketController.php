<?php

namespace App\Http\Controllers\Tiket;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Tiket;
use App\Models\Urgency;
use App\Models\Departemen;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Services\C45UrgencyPredictor;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Notification;
use App\Notifications\TicketCreatedNotification;
use App\Notifications\TicketAssignedNotification;
use App\Notifications\TicketRejectedNotification;
use App\Notifications\TicketReopenedNotification;
use App\Notifications\TicketCompletedNotification;

class TiketController extends Controller
{
    /**
     * Display a listing of tickets based on user role
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        $role = $user->role;
        
        // Base query dengan eager loading
        $query = Tiket::with([
            'user:id,nama,email,departemen_id',
            'user.departemen:id,nama_departemen',
            'teknisi:id,nama,email,departemen_id',
            'teknisi.departemen:id,nama_departemen',
            'departemen:id,nama_departemen',
            'urgency:id,urgency,jam',
            'recommendedUrgency:id,urgency,jam'
        ]);

        // Filter berdasarkan role
        switch ($role) {
            case 'user':
                // User hanya lihat tiket miliknya (kecuali yang sudah closed)
                $query->where('user_id', $user->id)
                    ->whereIn('status', ['open', 'pending', 'progress', 'finish']);
                break;
                
            case 'admin':
                // Admin lihat tiket yang perlu di-handle: open, pending, progress, finish
                $query->whereIn('status', ['open', 'pending', 'progress', 'finish']);
                break;
                
            case 'teknisi':
                // Teknisi hanya lihat tiket yang di-assign ke dia (kecuali closed)
                $query->where('teknisi_id', $user->id)
                    ->whereIn('status', ['pending', 'progress', 'finish']);
                break;
                
            case 'administrator':
                // Administrator bisa lihat semua (kecuali closed di index)
                $query->whereIn('status', ['open', 'pending', 'progress', 'finish']);
                break;
        }

        // Filter by status (optional)
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter by search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('nomor', 'like', "%{$search}%")
                  ->orWhere('judul', 'like', "%{$search}%")
                  ->orWhere('keterangan', 'like', "%{$search}%");
            });
        }

        $tikets = $query->orderBy('tanggal', 'desc')->paginate(15);

        // Data untuk dropdown
        $departemens = Departemen::orderBy('nama_departemen')->get();
        $urgencies = Urgency::orderBy('jam')->get();
        
        // Teknisi berdasarkan departemen user (untuk admin)
        if (in_array($role, ['admin', 'administrator'])) {
            if ($role === 'administrator') {
                $teknisis = User::where('role', 'teknisi')->orderBy('nama')->get();
            } else {
                $teknisis = User::where('role', 'teknisi')
                    ->where('departemen_id', $user->departemen_id)
                    ->orderBy('nama')
                    ->get();
            }
        } else {
            $teknisis = collect();
        }

        // Count per status untuk badge
        $statusCounts = [
            'open' => Tiket::where($this->getBaseFilter($role, $user))->where('status', 'open')->count(),
            'pending' => Tiket::where($this->getBaseFilter($role, $user))->where('status', 'pending')->count(),
            'progress' => Tiket::where($this->getBaseFilter($role, $user))->where('status', 'progress')->count(),
            'finish' => Tiket::where($this->getBaseFilter($role, $user))->where('status', 'finish')->count(),
        ];

        return view('tiket.index', compact('tikets', 'departemens', 'urgencies', 'teknisis', 'role', 'statusCounts'));
    }

    /**
     * Get base filter for status count
     */
    private function getBaseFilter($role, $user)
    {
        return function($query) use ($role, $user) {
            switch ($role) {
                case 'user':
                    $query->where('user_id', $user->id);
                    break;
                case 'teknisi':
                    $query->where('teknisi_id', $user->id);
                    break;
            }
        };
    }

    /**
     * Store a newly created ticket
     */
    /**
 * Store a newly created ticket
 */
/**
 * Store a newly created ticket
 */
public function store(Request $request)
{
    $validated = $request->validate([
        'judul' => 'required|string|max:255',
        'keterangan' => 'required|string',
        'gambar' => 'nullable|image|mimes:jpeg,png,jpg|max:2048'
    ], [
        'judul.required' => 'Judul tiket wajib diisi',
        'judul.max' => 'Judul maksimal 255 karakter',
        'keterangan.required' => 'Keterangan masalah wajib diisi',
        'gambar.image' => 'File harus berupa gambar',
        'gambar.max' => 'Ukuran gambar maksimal 2MB',
    ]);

    // Validasi user punya departemen
    $user = Auth::user();
    
    if (!$user->departemen_id) {
        return redirect()->back()
            ->withInput()
            ->with('error', 'Anda belum terdaftar di departemen manapun. Silakan hubungi administrator.');
    }

    DB::beginTransaction();
    
    $gambarPath = null;
    
    try {
        // ========================================
        // 1. UPLOAD GAMBAR
        // ========================================
        if ($request->hasFile('gambar')) {
            try {
                $gambarPath = $request->file('gambar')->store('tiket', 'public');
            } catch (\Exception $e) {
                Log::error('Upload gambar gagal: ' . $e->getMessage());
                throw new \Exception('Gagal mengupload gambar');
            }
        }

        // ========================================
        // 2. LOAD DEPARTEMEN
        // ========================================
        $user->load('departemen');
        $namaDepartemen = optional($user->departemen)->nama_departemen ?? 'Unknown';

        // ========================================
        // 3. PREDIKSI C4.5 (DENGAN FALLBACK)
        // ========================================
        $prediction = [
            'tipe_masalah' => null,
            'kata_kunci' => null,
            'dept_terdampak' => $namaDepartemen,
            'recommended_urgency_id' => null,
            'confidence_score' => null,
            'recommended_urgency' => 'Menunggu Review Admin'
        ];

        try {
            // Cek apakah class C45UrgencyPredictor ada
            if (class_exists('App\Services\C45UrgencyPredictor')) {
                $predictor = new \App\Services\C45UrgencyPredictor();
                $predictionResult = $predictor->predict(
                    $request->keterangan,
                    $namaDepartemen
                );
                
                // Merge hasil prediksi jika berhasil
                if (is_array($predictionResult)) {
                    $prediction = array_merge($prediction, $predictionResult);
                }
            }
        } catch (\Exception $e) {
            // Log error tapi jangan gagalkan pembuatan tiket
            Log::warning('C4.5 Prediction failed (tiket tetap dibuat): ' . $e->getMessage());
        }

        // ========================================
        // 4. GENERATE NOMOR TIKET
        // ========================================
        $nomor = $this->generateNomorTiket();

        // ========================================
        // 5. SIMPAN TIKET KE DATABASE
        // ========================================
        $tiket = Tiket::create([
            'user_id' => $user->id,
            'departemen_id' => $user->departemen_id,
            'nomor' => $nomor,
            'tanggal' => now(),
            'judul' => $request->judul,
            'keterangan' => $request->keterangan,
            'gambar' => $gambarPath,
            'status' => 'open',
            'tipe_masalah' => $prediction['tipe_masalah'],
            'kata_kunci' => $prediction['kata_kunci'],
            'dept_terdampak' => $prediction['dept_terdampak'],
            'recommended_urgency_id' => $prediction['recommended_urgency_id'],
            'confidence_score' => $prediction['confidence_score'],
        ]);

        DB::commit();

        // ========================================
        // 6. KIRIM NOTIFIKASI (OPTIONAL)
        // ========================================
        try {
            // Cari admin di departemen yang sama
            $admins = User::where('role', 'admin')
                ->where('departemen_id', $user->departemen_id)
                ->get();

            // Jika tidak ada, cari semua admin
            if ($admins->isEmpty()) {
                $admins = User::whereIn('role', ['admin', 'administrator'])->get();
            }

            // Kirim notifikasi jika class notification ada
            if ($admins->isNotEmpty() && class_exists('App\Notifications\TicketCreatedNotification')) {
                Notification::send($admins, new \App\Notifications\TicketCreatedNotification($tiket));
            }
        } catch (\Exception $e) {
            // Log saja, jangan gagalkan
            Log::warning('Notifikasi tiket baru gagal: ' . $e->getMessage());
        }

        // ========================================
        // 7. SUCCESS REDIRECT
        // ========================================
        $recommendedText = $prediction['recommended_urgency'] ?? 'Menunggu Review Admin';
        
        return redirect()->route('tiket.index')
            ->with('success', "Tiket #{$nomor} berhasil dibuat! Rekomendasi urgency: {$recommendedText}");

    } catch (\Exception $e) {
        DB::rollback();
        
        // Hapus gambar jika ada error
        if ($gambarPath && Storage::disk('public')->exists($gambarPath)) {
            Storage::disk('public')->delete($gambarPath);
        }

        // Log error lengkap
        Log::error('Error creating ticket:', [
            'message' => $e->getMessage(),
            'file' => $e->getFile(),
            'line' => $e->getLine(),
            'user_id' => $user->id,
            'departemen_id' => $user->departemen_id,
            'trace' => $e->getTraceAsString()
        ]);

        // Tampilkan error detail jika debug mode
        $errorDetail = config('app.debug') 
            ? ' [Error: ' . $e->getMessage() . ' di line ' . $e->getLine() . ']'
            : '';

        return redirect()->back()
            ->withInput()
            ->with('error', 'Gagal membuat tiket.' . $errorDetail);
    }
}



    /**
     * Generate nomor tiket otomatis
     */
    private function generateNomorTiket()
    {
        $prefix = 'TKT-' . date('Ymd');
        $last = Tiket::where('nomor', 'like', $prefix . '%')
            ->orderBy('nomor', 'desc')
            ->first();

        if ($last) {
            $lastNumber = intval(substr($last->nomor, -3));
            $newNumber = str_pad($lastNumber + 1, 3, '0', STR_PAD_LEFT);
        } else {
            $newNumber = '001';
        }

        return $prefix . '-' . $newNumber;
    }

    /**
     * Show ticket details (AJAX)
     */
    public function show($id)
    {
        try {
            $tiket = Tiket::with([
                'user.departemen',
                'teknisi.departemen',
                'departemen',
                'urgency',
                'recommendedUrgency'
            ])->findOrFail($id);

            $teknisis = User::where('role', 'teknisi')
                ->where('departemen_id', Auth::user()->departemen_id)
                ->get();

            return response()->json([
                'success' => true,
                'tiket' => $tiket,
                'urgencies' => Urgency::orderBy('jam')->get(),
                'teknisis' => $teknisis,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Tiket tidak ditemukan: ' . $e->getMessage()
            ], 404);
        }
    }

    /**
     * Admin assign tiket ke teknisi dengan urgency
     */
    public function assign(Request $request, $id)
{
    $request->validate([
        'teknisi_id' => 'required|exists:users,id',
        'urgency_id' => 'required|exists:urgency,id',
    ]);

    $tiket = Tiket::findOrFail($id);
    $urgency = Urgency::findOrFail($request->urgency_id);
    
    // PERBAIKI: Hitung deadline dari waktu DIBUAT + jam SLA
    $tanggalSelesai = Carbon::parse($tiket->tanggal)->addHours($urgency->jam);
    
    $tiket->update([
        'teknisi_id' => $request->teknisi_id,
        'urgency_id' => $request->urgency_id,
        'status' => 'pending',
        'tanggal_selesai' => $tanggalSelesai, // PENTING: dari tanggal dibuat!
        'catatan_admin' => $request->catatan_admin,
    ]);

    return response()->json([
        'success' => true,
        'message' => 'Tiket berhasil di-assign ke teknisi'
    ]);
}



    /**
     * Admin unassign tiket (kembalikan ke open)
     */
    public function unassign($id)
    {
        DB::beginTransaction();
        try {
            $tiket = Tiket::findOrFail($id);
            $user = Auth::user();

            if (!in_array($user->role, ['admin', 'administrator'])) {
                throw new \Exception('Hanya admin yang dapat unassign tiket');
            }

            if ($tiket->status !== 'pending') {
                throw new \Exception('Hanya tiket dengan status PENDING yang bisa di-unassign');
            }

            // Reset ke status open
            $tiket->status = 'open';
            $tiket->teknisi_id = null;
            $tiket->urgency_id = null;
            $tiket->tanggal_selesai = null;
            $tiket->catatan = 'Tiket di-unassign oleh admin pada ' . now()->format('d/m/Y H:i');
            $tiket->save();

            DB::commit();

            return redirect()->back()->with('success', "Tiket #{$tiket->nomor} berhasil di-unassign dan kembali ke status OPEN");

        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    /**
     * Teknisi terima tiket
     */
    public function accept($id)
    {
        DB::beginTransaction();
        try {
            $tiket = Tiket::with(['user', 'departemen'])->findOrFail($id);

            if (Auth::id() != $tiket->teknisi_id) {
                throw new \Exception('Anda tidak memiliki akses untuk menerima tiket ini');
            }

            if ($tiket->status !== 'pending') {
                throw new \Exception('Tiket tidak dalam status PENDING');
            }

            $tiket->status = 'progress';
            $tiket->save();

            DB::commit();

            return redirect()->back()->with('success', "Tiket #{$tiket->nomor} berhasil diterima dan sedang dikerjakan");

        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    /**
     * Teknisi tolak tiket dengan alasan
     */
    public function reject(Request $request, $id)
    {
        $request->validate([
            'alasan_penolakan' => 'required|string|max:500'
        ], [
            'alasan_penolakan.required' => 'Alasan penolakan wajib diisi'
        ]);

        DB::beginTransaction();
        try {
            $tiket = Tiket::with(['user', 'teknisi', 'departemen', 'urgency'])->findOrFail($id);

            if (Auth::id() != $tiket->teknisi_id) {
                throw new \Exception('Anda tidak memiliki akses untuk menolak tiket ini');
            }

            if ($tiket->status !== 'pending') {
                throw new \Exception('Tiket tidak dalam status PENDING');
            }

            // Reset ke status open
            $tiket->status = 'open';
            $tiket->catatan = 'DITOLAK oleh teknisi: ' . $request->alasan_penolakan;
            $tiket->teknisi_id = null;
            $tiket->urgency_id = null;
            $tiket->tanggal_selesai = null;
            $tiket->save();

            DB::commit();

            // Kirim notifikasi ke admin
            try {
                $admins = User::where('role', 'admin')
                    ->whereIn('departemen_id', function($query) {
                        $query->select('id')
                            ->from('departemen')
                            ->whereIn('nama_departemen', ['IT', 'GA', 'EHS']);
                    })
                    ->get();

                if ($admins->count() > 0) {
                    Notification::send($admins, new TicketRejectedNotification($tiket, $request->alasan_penolakan));
                }
            } catch (\Exception $e) {
                Log::error('Notifikasi reject error: ' . $e->getMessage());
            }

            return redirect()->back()->with('success', "Tiket #{$tiket->nomor} ditolak dan dikembalikan ke admin");

        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    /**
     * Teknisi tandai selesai dengan solusi
     */
    public function complete(Request $request, $id)
    {
        $request->validate([
            'solusi' => 'required|string'
        ], [
            'solusi.required' => 'Solusi/catatan penyelesaian wajib diisi'
        ]);

        DB::beginTransaction();
        try {
            $tiket = Tiket::with(['user', 'teknisi', 'departemen', 'urgency'])->findOrFail($id);

            if (Auth::id() != $tiket->teknisi_id) {
                throw new \Exception('Anda tidak memiliki akses untuk menyelesaikan tiket ini');
            }

            if ($tiket->status !== 'progress') {
                throw new \Exception('Tiket tidak dalam status PROGRESS');
            }

            $tiket->status = 'finish';
            $tiket->solusi = $request->solusi;
            $tiket->tanggal_selesai = Carbon::now();
            $tiket->save();

            DB::commit();

            // Kirim notifikasi ke admin
            try {
                $admins = User::where('role', 'admin')
                    ->whereIn('departemen_id', function($query) {
                        $query->select('id')
                            ->from('departemen')
                            ->whereIn('nama_departemen', ['IT', 'GA', 'EHS']);
                    })
                    ->get();

                if ($admins->count() > 0) {
                    Notification::send($admins, new TicketCompletedNotification($tiket));
                }
            } catch (\Exception $e) {
                Log::error('Notifikasi complete error: ' . $e->getMessage());
            }

            return redirect()->back()->with('success', "Tiket #{$tiket->nomor} berhasil diselesaikan dan menunggu verifikasi admin");

        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    /**
     * Admin close/verifikasi tiket yang sudah finish
     */
    public function close($id)
    {
        DB::beginTransaction();
        try {
            $tiket = Tiket::findOrFail($id);
            $user = Auth::user();

            if (!in_array($user->role, ['admin', 'administrator'])) {
                throw new \Exception('Hanya admin yang dapat menutup tiket');
            }

            if ($tiket->status !== 'finish') {
                throw new \Exception('Tiket harus dalam status FINISH sebelum ditutup');
            }

            $tiket->status = 'closed';
            $tiket->save();

            DB::commit();

            return redirect()->back()->with('success', "Tiket #{$tiket->nomor} berhasil ditutup");

        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    /**
     * Administrator reopen tiket yang sudah closed
     */
    public function reopen($id)
    {
        DB::beginTransaction();
        try {
            $tiket = Tiket::with(['user', 'departemen', 'teknisi', 'urgency'])->findOrFail($id);
            $user = Auth::user();

            if ($user->role !== 'administrator') {
                throw new \Exception('Hanya administrator yang dapat reopen tiket');
            }

            if ($tiket->status !== 'closed') {
                throw new \Exception('Hanya tiket dengan status CLOSED yang bisa di-reopen');
            }

            // Reset ke status open
            $tiket->status = 'open';
            $tiket->teknisi_id = null;
            $tiket->urgency_id = null;
            $tiket->tanggal_selesai = null;
            $tiket->catatan = 'REOPENED oleh Administrator pada ' . now()->format('d/m/Y H:i');
            $tiket->save();

            DB::commit();

            // Kirim notifikasi ke admin
            try {
                $admins = User::where('role', 'admin')->get();
                
                if ($admins->count() > 0) {
                    $tiket->load(['user', 'departemen']);
                    Notification::send($admins, new TicketReopenedNotification($tiket));
                }
            } catch (\Exception $e) {
                Log::error('Notifikasi reopen error: ' . $e->getMessage());
            }

            return redirect()->route('tiket.laporan')->with('success', "Tiket #{$tiket->nomor} berhasil di-reopen");

        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->route('tiket.laporan')->with('error', $e->getMessage());
        }
    }

    /**
     * Laporan tiket (termasuk closed)
     */
    public function laporan(Request $request)
    {
        $query = Tiket::with([
            'user:id,nama,email,departemen_id',
            'user.departemen:id,nama_departemen',
            'teknisi:id,nama,email',
            'departemen:id,nama_departemen',
            'urgency:id,urgency,jam'
        ]);

        // Filter
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('departemen_id')) {
            $query->where('departemen_id', $request->departemen_id);
        }

        if ($request->filled('date_from')) {
            $query->whereDate('tanggal', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('tanggal', '<=', $request->date_to);
        }

        $tikets = $query->orderBy('tanggal', 'desc')->paginate(20);

        $departemens = Departemen::orderBy('nama_departemen')->get();

        return view('tiket.laporan', compact('tikets', 'departemens'));
    }

    /**
 * Analytics - Evaluasi akurasi C4.5
 */
/**
 * Analytics & Evaluasi C4.5
 */
public function analytics(Request $request)
{
    // Get filters
    $filterDate = $request->input('filter_date');
    $filterMatch = $request->input('filter_match');

    // Query tiket dengan rekomendasi
    $query = DB::table('tiket')
        ->select(
            'tiket.id',
            'tiket.nomor',
            'tiket.judul',
            'tiket.tanggal',
            'tiket.tipe_masalah',
            'tiket.dept_terdampak',
            'tiket.recommended_urgency_id',
            'tiket.urgency_id',
            'u1.urgency as recommended',
            'u2.urgency as actual',
            'users.nama as username', // ✅ SUDAH BENAR: users.nama
            DB::raw('CASE WHEN tiket.recommended_urgency_id = tiket.urgency_id THEN 1 ELSE 0 END as is_match')
        )
        ->leftJoin('urgency as u1', 'tiket.recommended_urgency_id', '=', 'u1.id')
        ->leftJoin('urgency as u2', 'tiket.urgency_id', '=', 'u2.id')
        ->leftJoin('users', 'tiket.user_id', '=', 'users.id')
        ->whereNotNull('tiket.recommended_urgency_id')
        ->whereNotNull('tiket.urgency_id');

    // Apply date filter
    if ($filterDate) {
        $dates = explode(' to ', $filterDate);
        if (count($dates) == 2) {
            $query->whereBetween('tiket.tanggal', [
                $dates[0] . ' 00:00:00',
                $dates[1] . ' 23:59:59'
            ]);
        } else {
            $query->whereDate('tiket.tanggal', $dates[0]);
        }
    }

    // Apply match filter
    if ($filterMatch !== null && $filterMatch !== '') {
        if ($filterMatch == '1') {
            $query->whereRaw('tiket.recommended_urgency_id = tiket.urgency_id');
        } else {
            $query->whereRaw('tiket.recommended_urgency_id != tiket.urgency_id');
        }
    }

    // Get all evaluations dengan pagination
    $evaluations = $query->orderBy('tiket.tanggal', 'desc')
        ->paginate(20)
        ->appends($request->all());

    // Calculate statistics
    $statsQuery = DB::table('tiket')
        ->select(
            DB::raw('COUNT(DISTINCT tiket.id) as total'),
            DB::raw('SUM(CASE WHEN tiket.recommended_urgency_id = tiket.urgency_id THEN 1 ELSE 0 END) as correct')
        )
        ->whereNotNull('recommended_urgency_id')
        ->whereNotNull('urgency_id');

    // Apply same filters untuk stats
    if ($filterDate) {
        $dates = explode(' to ', $filterDate);
        if (count($dates) == 2) {
            $statsQuery->whereBetween('tiket.tanggal', [
                $dates[0] . ' 00:00:00',
                $dates[1] . ' 23:59:59'
            ]);
        } else {
            $statsQuery->whereDate('tiket.tanggal', $dates[0]);
        }
    }

    if ($filterMatch !== null && $filterMatch !== '') {
        if ($filterMatch == '1') {
            $statsQuery->whereRaw('tiket.recommended_urgency_id = tiket.urgency_id');
        } else {
            $statsQuery->whereRaw('tiket.recommended_urgency_id != tiket.urgency_id');
        }
    }

    $stats = $statsQuery->first();

    // Calculate accuracy
    $total = $stats->total ?? 0;
    $correct = $stats->correct ?? 0;
    $accuracy = $total > 0 ? round(($correct / $total) * 100, 2) : 0;

    // Get accuracy by urgency level
    $byUrgencyQuery = DB::table('tiket')
        ->select(
            'u2.urgency as urgency',
            DB::raw('COUNT(DISTINCT tiket.id) as total'),
            DB::raw('SUM(CASE WHEN tiket.recommended_urgency_id = tiket.urgency_id THEN 1 ELSE 0 END) as correct'),
            DB::raw('ROUND((SUM(CASE WHEN tiket.recommended_urgency_id = tiket.urgency_id THEN 1 ELSE 0 END) / COUNT(DISTINCT tiket.id)) * 100, 2) as accuracy')
        )
        ->leftJoin('urgency as u2', 'tiket.urgency_id', '=', 'u2.id')
        ->whereNotNull('tiket.recommended_urgency_id')
        ->whereNotNull('tiket.urgency_id');

    // Apply same filters
    if ($filterDate) {
        $dates = explode(' to ', $filterDate);
        if (count($dates) == 2) {
            $byUrgencyQuery->whereBetween('tiket.tanggal', [
                $dates[0] . ' 00:00:00',
                $dates[1] . ' 23:59:59'
            ]);
        } else {
            $byUrgencyQuery->whereDate('tiket.tanggal', $dates[0]);
        }
    }

    if ($filterMatch !== null && $filterMatch !== '') {
        if ($filterMatch == '1') {
            $byUrgencyQuery->whereRaw('tiket.recommended_urgency_id = tiket.urgency_id');
        } else {
            $byUrgencyQuery->whereRaw('tiket.recommended_urgency_id != tiket.urgency_id');
        }
    }

    $byUrgency = $byUrgencyQuery->groupBy('u2.id', 'u2.urgency')
        ->orderBy('urgency')
        ->get();

    return view('tiket.analytics', compact(
        'evaluations',
        'total',
        'accuracy',
        'byUrgency',
        'filterDate',
        'filterMatch'
    ));
}



}
