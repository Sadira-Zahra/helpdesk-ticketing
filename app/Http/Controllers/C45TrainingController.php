<?php

namespace App\Http\Controllers;

use App\Services\C45UrgencyPredictor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\TrainingDataImport;

class C45TrainingController extends Controller
{
    /**
     * Halaman training
     */
    public function index()
    {
        $model = DB::table('c45_model')->where('id', 1)->first();
        $trainingCount = DB::table('tiket_training')->count();

        return view('tiket.training', compact('model', 'trainingCount'));
    }

    /**
 * Import data training dari Excel
 */
/**
 * Import data training dari Excel/CSV
 */
/**
 * Import data training dari Excel/CSV
 */
public function import(Request $request)
{
    $request->validate([
        'file' => 'required|mimes:xlsx,xls,csv|max:2048'
    ]);

    try {
        DB::beginTransaction();

        $file = $request->file('file');
        $data = $this->readCSV($file);

        $imported = 0;
        $skipped = 0;

        foreach ($data as $index => $row) {
            // Skip 3 baris pertama (header)
            if ($index < 3) continue;

            // Pastikan ada minimal 6 kolom
            if (count($row) < 6) continue;

            // Parse data dari kolom
            $tanggal = !empty($row[1]) ? $this->parseDate($row[1]) : null;
            $deskripsi = trim($row[2] ?? '');
            $kategori_urgency = trim($row[3] ?? ''); // Ini adalah Urgent/High/Medium/Low
            $sla_text = trim($row[4] ?? '');
            $actual_text = trim($row[5] ?? '');

            // Skip jika deskripsi kosong
            if (empty($deskripsi) || empty($kategori_urgency)) {
                $skipped++;
                continue;
            }

            // Parse SLA dan Actual (format: "6 Jam", "24 Jam")
            $sla = $this->parseJam($sla_text);
            $actual = $this->parseJam($actual_text);

            // Extract fitur dari deskripsi
            $tipe_masalah = $this->detectProblemType($deskripsi);
            $kata_kunci = $this->extractKeywords($deskripsi);
            $dept_terdampak = $this->assessImpact($deskripsi);

            // Insert
            DB::table('tiket_training')->insert([
                'tanggal_problem' => $tanggal ?: now()->format('Y-m-d'),
                'deskripsi_problem' => $deskripsi,
                'kategori' => $tipe_masalah, // Hasil deteksi
                'sla_target_hrs' => $sla,
                'actual_hrs' => $actual,
                'urgency_level' => $kategori_urgency, // Dari CSV
                'created_at' => now(),
                'updated_at' => now()
            ]);

            $imported++;
        }

        DB::commit();

        $total = DB::table('tiket_training')->count();

        return redirect()->route('tiket.training.index')
            ->with('success', "Berhasil import {$imported} data! (Skipped: {$skipped}). Total data training: {$total}");

    } catch (\Exception $e) {
        DB::rollback();
        return redirect()->back()
            ->with('error', 'Gagal import: ' . $e->getMessage());
    }
}

/**
 * Parse format jam (contoh: "6 Jam", "24 Jam", "3 Jam")
 */
private function parseJam($text)
{
    if (empty($text)) return 24;
    
    // Extract angka dari text
    preg_match('/(\d+)/', $text, $matches);
    return isset($matches[1]) ? (int)$matches[1] : 24;
}

/**
 * Deteksi tipe masalah dari deskripsi
 */
private function detectProblemType($text)
{
    $text = strtolower($text);
    
    // Printer
    if (preg_match('/(printer|print|cetak|tinta|laser|lq|epson)/i', $text)) {
        return 'Printer';
    }
    
    // Laptop/PC
    if (preg_match('/(laptop|pc|komputer|computer|mini pc)/i', $text)) {
        return 'Hardware';
    }
    
    // Network
    if (preg_match('/(internet|jaringan|wifi|lan|network|switch|kabel lan|koneksi)/i', $text)) {
        return 'Network';
    }
    
    // Software/Aplikasi
    if (preg_match('/(install|software|aplikasi|update|windows|office|antivirus|cortex|framework|hrp|autocad|email|outlook|password)/i', $text)) {
        return 'Software';
    }
    
    // Setting/Konfigurasi
    if (preg_match('/(setting|konfigurasi|deploy|join domain)/i', $text)) {
        return 'Configuration';
    }
    
    // Maintenance
    if (preg_match('/(maintenance|service|repair|perbaikan|pengecekan)/i', $text)) {
        return 'Maintenance';
    }
    
    return 'Lainnya';
}

/**
 * Extract keywords dari deskripsi
 */
private function extractKeywords($text)
{
    $keywords = [
        'printer', 'laptop', 'pc', 'komputer', 'server',
        'repair', 'perbaikan', 'setting', 'install', 
        'replacement', 'update', 'maintenance',
        'jaringan', 'network', 'internet', 'wifi',
        'email', 'password', 'software', 'hardware'
    ];
    
    $found = [];
    $text = strtolower($text);
    
    foreach ($keywords as $keyword) {
        if (strpos($text, $keyword) !== false) {
            $found[] = $keyword;
        }
    }
    
    return !empty($found) ? implode(', ', array_slice($found, 0, 3)) : 'umum';
}

/**
 * Assess impact dari deskripsi
 */
private function assessImpact($text)
{
    $text = strtolower($text);
    
    // Cek mentions beberapa departemen/area
    $dept_count = 0;
    $depts = ['ppc', 'qc', 'fa', 'mtn', 'asm', 'et', 'ikn', 'eng', 'hr', 'ga', 'warehouse'];
    
    foreach ($depts as $dept) {
        if (strpos($text, $dept) !== false) {
            $dept_count++;
        }
    }
    
    if ($dept_count >= 2 || preg_match('/(semua|seluruh|all|server|network)/i', $text)) {
        return 'Multiple Dept';
    }
    
    return 'Single Dept';
}


/**
 * Read CSV file
 */
private function readCSV($file)
{
    $data = [];
    $handle = fopen($file->getRealPath(), 'r');

    while (($row = fgetcsv($handle, 1000, ',')) !== false) {
        $data[] = $row;
    }

    fclose($handle);
    return $data;
}

/**
 * Parse tanggal dari berbagai format
 */
private function parseDate($dateValue)
{
    if (empty($dateValue)) {
        return now()->format('Y-m-d');
    }

    // Jika sudah format YYYY-MM-DD
    if (preg_match('/^\d{4}-\d{2}-\d{2}$/', $dateValue)) {
        return $dateValue;
    }

    // Parse format lain
    try {
        return \Carbon\Carbon::parse($dateValue)->format('Y-m-d');
    } catch (\Exception $e) {
        return now()->format('Y-m-d');
    }
}

/**
 * Mapping SLA (jam) ke Urgency Level
 */
private function mapSLAtoUrgency($slaHours)
{
    if ($slaHours <= 4) {
        return 'Critical'; // 0-4 jam
    } elseif ($slaHours <= 24) {
        return 'High'; // 5-24 jam
    } elseif ($slaHours <= 48) {
        return 'Medium'; // 25-48 jam
    } else {
        return 'Low'; // > 48 jam
    }
}

    /**
 * Train model C4.5
 */
/**
 * Train model C4.5
 */
public function train()
{
    try {
        $predictor = new C45UrgencyPredictor();
        
        // Ambil data training dari DB
        $trainingData = DB::table('tiket_training')
            ->select('deskripsi_problem', 'kategori', 'sla_target_hrs', 'urgency_level')
            ->whereNotNull('urgency_level')
            ->get()
            ->map(function($item) {
                // Transform ke format C4.5
                return (object) [
                    'kata_kunci' => $this->extractKeywords($item->deskripsi_problem),
                    'tipe_masalah' => $item->kategori,
                    'dept_terdampak' => $this->assessImpact($item->deskripsi_problem),
                    'sla_hrs' => $item->sla_target_hrs,
                    'urgency_level' => $item->urgency_level
                ];
            })
            ->toArray();
        
        if (count($trainingData) < 10) {
            return redirect()->back()
                ->with('error', 'Data training minimal 10 record. Saat ini: ' . count($trainingData));
        }
        
        // Train model
        $result = $predictor->train($trainingData);
        
        return redirect()->route('tiket.training.index')
            ->with('success', "Model berhasil di-train! Akurasi: {$result['accuracy']}%. Total data: {$result['data_count']}. Rules: " . count($result['rules']));

    } catch (\Exception $e) {
        return redirect()->back()
            ->with('error', 'Gagal training: ' . $e->getMessage());
    }
}


    /**
 * Lihat pohon keputusan
 */
public function viewTree()
{
    $model = DB::table('c45_model')->where('id', 1)->first();
    
    if (!$model) {
        return redirect()->route('tiket.training.index')
            ->with('error', 'Model belum di-train. Silakan train terlebih dahulu.');
    }

    $tree = json_decode($model->tree_json, true);
    $rules = json_decode($model->rules_json, true);
    
    // Fallback jika tree kosong
    if (empty($tree)) {
        $tree = [
            'type' => 'leaf',
            'class' => 'Medium'
        ];
    }
    
    if (empty($rules)) {
        $rules = [];
    }

    return view('tiket.decision_tree', compact('tree', 'rules'));
}


    /**
 * Download template Excel
 */
public function downloadTemplate()
{
    $filename = 'template_training_helpdesk.csv';
    
    $headers = [
        'Content-Type' => 'text/csv; charset=UTF-8',
        'Content-Disposition' => "attachment; filename=\"{$filename}\"",
    ];

    $data = [
        // Header (baris 1-2, akan diskip saat import)
        ['COMPUTER, SERVER DEVICE PROBLEM'],
        ['FY 25 (Apr 25 - Mar 26)'],
        // Kolom (baris 3)
        ['No.', 'Tanggal Problem', 'Deskripsi Problem', 'Kategori', 'SLA target (Hrs)', 'Actual (Hrs)'],
        // Sample data
        ['1', '3/10/2025', 'Perbaikan pc di ASM dan IKN Machining', 'High', '6 Jam', '1 Jam'],
        ['2', '3/13/2025', 'Repair printer LQ di PPC', 'High', '6 Jam', '1 Jam'],
        ['3', '3/21/2025', 'Repair laptop user', 'Medium', '24 Jam', '3 Jam'],
        ['4', '3/25/2025', 'Setting Jam digital ET & ASM', 'Urgent', '3 Jam', '1 Jam'],
        ['5', '4/8/2025', 'Perbaikan Pc dan Printer di IKN Machining', 'High', '6 Jam', '1 Jam'],
        ['6', '9/29/2025', 'Repair windows user FA ibu Peni', 'Low', '36 Jam', '24 Jam'],
    ];

    return response()->streamDownload(function () use ($data) {
        $file = fopen('php://output', 'w');
        // Add BOM for UTF-8
        fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));
        foreach ($data as $row) {
            fputcsv($file, $row);
        }
        fclose($file);
    }, $filename, $headers);
}



    /**
     * Hapus semua data training
     */
    public function deleteAll()
    {
        try {
            DB::table('tiket_training')->truncate();
            
            return redirect()->route('tiket.training.index')
                ->with('success', 'Semua data training berhasil dihapus.');
                
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Gagal menghapus data: ' . $e->getMessage());
        }
    }
}
