<?php

namespace App\Http\Controllers\Tiket;

use App\Models\Tiket;
use App\Models\Departemen;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class LaporanTiketController extends Controller
{
    /**
     * Display laporan tiket dengan filter
     */
    public function index(Request $request)
{
    $user = Auth::user();
    $role = $user->role;

    $startDate = $request->get('start_date', null);
    $endDate = $request->get('end_date', null);
    $departemenId = $request->get('departemen_id', null);
    $statusFilter = $request->get('status', 'closed');

    // Initialize variables
    $tikets = null; // ✅ UBAH: dari collect() jadi null
    $showData = false;
    $stats = [
        'total' => 0,
        'closed' => 0,
        'open' => 0,
        'in_progress' => 0
    ];

    if ($startDate && $endDate) {
        $showData = true;

        // Query laporan tiket dengan eager loading lengkap
        $query = Tiket::with([
            'user:id,nama,email,departemen_id',
            'user.departemen:id,nama_departemen',
            'teknisi:id,nama,email,departemen_id',
            'teknisi.departemen:id,nama_departemen',
            'departemen:id,nama_departemen',
            'urgency:id,urgency,jam'
        ])
        ->whereBetween('tanggal', [$startDate . ' 00:00:00', $endDate . ' 23:59:59'])
        ->orderBy('tanggal', 'desc');

        // Filter status
        if ($statusFilter !== 'all') {
            $query->where('status', $statusFilter);
        }

        // Filter berdasarkan role
        if ($role === 'user') {
            $query->where('user_id', $user->id);
        } elseif ($role === 'teknisi') {
            $query->where('teknisi_id', $user->id);
        } elseif ($role === 'administrator' && $departemenId) {
            $query->where('departemen_id', $departemenId);
        }

        $tikets = $query->paginate(20)->appends($request->query());

        // Get statistics
        $stats = $this->getStatistics($startDate, $endDate, $role, $user, $departemenId, $statusFilter);
    }

    // Get departemen list untuk filter
    $departemens = $role === 'administrator' 
        ? Departemen::orderBy('nama_departemen')->get() 
        : collect();

    return view('tiket.laporan_tiket', compact(
        'tikets',
        'startDate',
        'endDate',
        'departemens',
        'departemenId',
        'statusFilter',
        'role',
        'showData',
        'stats'
    ));
}

/**
 * Get Statistics
 */
private function getStatistics($startDate, $endDate, $role, $user, $departemenId = null, $statusFilter = 'closed')
{
    if (!$startDate || !$endDate) {
        return [
            'total' => 0,
            'closed' => 0,
            'open' => 0,
            'in_progress' => 0
        ];
    }

    $query = Tiket::whereBetween('tanggal', [$startDate . ' 00:00:00', $endDate . ' 23:59:59']);

    // Filter status jika bukan 'all'
    if ($statusFilter !== 'all') {
        $query->where('status', $statusFilter);
    }

    // Filter berdasarkan role
    if ($role === 'user') {
        $query->where('user_id', $user->id);
    } elseif ($role === 'teknisi') {
        $query->where('teknisi_id', $user->id);
    } elseif ($role === 'administrator' && $departemenId) {
        $query->where('departemen_id', $departemenId);
    }

    // Clone query untuk setiap status
    $total = (clone $query)->count();
    
    return [
        'total' => $total,
        'closed' => (clone $query)->where('status', 'closed')->count(),
        'open' => (clone $query)->where('status', 'open')->count(),
        'in_progress' => (clone $query)->where('status', 'in_progress')->count(),
    ];
}


    /**
     * Export laporan tiket ke Excel
     */
    public function export(Request $request)
    {
        $user = Auth::user();
        $role = $user->role;

        $startDate = $request->get('start_date');
        $endDate = $request->get('end_date');
        $departemenId = $request->get('departemen_id');
        $statusFilter = $request->get('status', 'closed');

        // Validasi tanggal
        if (!$startDate || !$endDate) {
            return redirect()->route('tiket.laporan')
                ->with('error', 'Tanggal harus diisi untuk ekspor data');
        }

        // Query sama seperti di index
        $query = Tiket::with([
            'user:id,nama,email,departemen_id',
            'user.departemen:id,nama_departemen',
            'teknisi:id,nama,email,departemen_id',
            'teknisi.departemen:id,nama_departemen',
            'departemen:id,nama_departemen',
            'urgency:id,urgency,jam'
        ])
        ->whereBetween('tanggal', [$startDate . ' 00:00:00', $endDate . ' 23:59:59'])
        ->orderBy('tanggal', 'desc');

        // Filter status
        if ($statusFilter !== 'all') {
            $query->where('status', $statusFilter);
        }

        // Filter berdasarkan role
        if ($role === 'user') {
            $query->where('user_id', $user->id);
        } elseif ($role === 'teknisi') {
            $query->where('teknisi_id', $user->id);
        } elseif ($role === 'administrator' && $departemenId) {
            $query->where('departemen_id', $departemenId);
        }

        $tikets = $query->get();

        // Cek jika tidak ada data
        if ($tikets->isEmpty()) {
            return redirect()->route('tiket.laporan', $request->query())
                ->with('error', 'Tidak ada data untuk diekspor dalam periode ini');
        }

        // Generate Excel
        try {
            $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
            $sheet = $spreadsheet->getActiveSheet();

            // Header
            $sheet->mergeCells('A1:L1');
            $sheet->setCellValue('A1', 'LAPORAN HELPDESK SYSTEM - TIKET ' . strtoupper($statusFilter));
            $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(16)->getColor()->setRGB('FFFFFF');
            $sheet->getStyle('A1')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setRGB('1e3a8a');
            $sheet->getStyle('A1')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
            $sheet->getRowDimension(1)->setRowHeight(30);

            // Periode
            $periodeText = 'Periode: ' . date('d/m/Y', strtotime($startDate)) . ' - ' . date('d/m/Y', strtotime($endDate));
            $sheet->mergeCells('A3:L3');
            $sheet->setCellValue('A3', $periodeText);
            $sheet->getStyle('A3')->getFont()->setItalic(true)->setSize(11);
            $sheet->getStyle('A3')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

            // Headers
            $headers = [
                'No', 'Nomor Tiket', 'Tanggal Dibuat', 'Judul', 'Keterangan',
                'User', 'Departemen', 'Urgency', 'Teknisi', 'Dept Teknisi',
                'Status', 'Tanggal Selesai'
            ];
            $sheet->fromArray($headers, null, 'A5');

            $headerStyle = $sheet->getStyle('A5:L5');
            $headerStyle->getFont()->setBold(true)->setSize(11)->getColor()->setRGB('FFFFFF');
            $headerStyle->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setRGB('2563eb');
            $headerStyle->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
            $headerStyle->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);

            // Data
            $row = 6;
            foreach ($tikets as $index => $tiket) {
                $data = [
                    $index + 1,
                    $tiket->nomor,
                    $tiket->tanggal ? $tiket->tanggal->format('d/m/Y H:i') : '-',
                    $tiket->judul,
                    $tiket->keterangan ?? '-',
                    $tiket->user ? $tiket->user->nama : '-',
                    $tiket->departemen ? $tiket->departemen->nama_departemen : '-',
                    $tiket->urgency ? $tiket->urgency->urgency : '-',
                    $tiket->teknisi ? $tiket->teknisi->nama : '-',
                    $tiket->teknisi && $tiket->teknisi->departemen ? $tiket->teknisi->departemen->nama_departemen : '-',
                    strtoupper($tiket->status),
                    $tiket->tanggal_selesai ? $tiket->tanggal_selesai->format('d/m/Y H:i') : '-',
                ];

                $sheet->fromArray($data, null, 'A' . $row);

                $dataStyle = $sheet->getStyle('A' . $row . ':L' . $row);
                $dataStyle->getAlignment()->setWrapText(true);
                $dataStyle->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);

                if ($index % 2 == 0) {
                    $dataStyle->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setRGB('F3F4F6');
                }

                $row++;
            }

            // Column widths
            $sheet->getColumnDimension('A')->setWidth(5);
            $sheet->getColumnDimension('B')->setWidth(15);
            $sheet->getColumnDimension('C')->setWidth(18);
            $sheet->getColumnDimension('D')->setWidth(25);
            $sheet->getColumnDimension('E')->setWidth(30);
            $sheet->getColumnDimension('F')->setWidth(15);
            $sheet->getColumnDimension('G')->setWidth(18);
            $sheet->getColumnDimension('H')->setWidth(12);
            $sheet->getColumnDimension('I')->setWidth(15);
            $sheet->getColumnDimension('J')->setWidth(18);
            $sheet->getColumnDimension('K')->setWidth(10);
            $sheet->getColumnDimension('L')->setWidth(18);

            // Download
            $fileName = 'Laporan_Tiket_' . now()->format('Y-m-d_His') . '.xlsx';
            $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);

            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            header('Content-Disposition: attachment;filename="' . $fileName . '"');
            header('Cache-Control: max-age=0');

            $writer->save('php://output');
            exit;

        } catch (\Exception $e) {
            Log::error('Export error: ' . $e->getMessage());
            return redirect()->route('tiket.laporan', $request->query())
                ->with('error', 'Gagal export: ' . $e->getMessage());
        }
    }
}
