<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Font;

class LaporanTiketExport implements FromArray, WithHeadings, WithStyles, WithColumnWidths
{
    protected $tikets;
    protected $startDate;
    protected $endDate;
    protected $data = [];

    public function __construct($tikets, $startDate = null, $endDate = null)
    {
        $this->tikets = $tikets;
        $this->startDate = $startDate;
        $this->endDate = $endDate;
        $this->prepareData();
    }

    /**
     * Prepare data untuk export
     */
    private function prepareData()
    {
        $rows = [];
        
        foreach ($this->tikets as $index => $tiket) {
            $rows[] = [
                'no' => $index + 1,
                'nomor_tiket' => $tiket->nomor,
                'tanggal_dibuat' => $tiket->tanggal ? $tiket->tanggal->format('d/m/Y H:i') : '-',
                'judul' => $tiket->judul,
                'user' => $tiket->user ? $tiket->user->nama : '-',
                'departemen_user' => $tiket->departemen ? $tiket->departemen->nama_departemen : '-',
                'urgency' => $tiket->urgency ? $tiket->urgency->urgency : '-',
                'teknisi' => $tiket->teknisi ? $tiket->teknisi->nama : '-',
                'departemen_teknisi' => $tiket->teknisi && $tiket->teknisi->departemen ? $tiket->teknisi->departemen->nama_departemen : '-',
                'status' => strtoupper($tiket->status),
                'tanggal_selesai' => $tiket->tanggal_selesai ? $tiket->tanggal_selesai->format('d/m/Y H:i') : '-',
            ];
        }

        $this->data = $rows;
    }

    /**
     * Return array data
     */
    public function array(): array
    {
        return $this->data;
    }

    /**
     * Heading untuk header tabel
     */
    public function headings(): array
    {
        return [
            'No',
            'Nomor Tiket',
            'Tanggal Dibuat',
            'Judul',
            'User',
            'Departemen User',
            'Urgency',
            'Teknisi',
            'Departemen Teknisi',
            'Status',
            'Tanggal Selesai',
        ];
    }

    /**
     * Column width
     */
    public function columnWidths(): array
    {
        return [
            'A' => 5,      // No
            'B' => 15,     // Nomor Tiket
            'C' => 18,     // Tanggal Dibuat
            'D' => 30,     // Judul
            'E' => 15,     // User
            'F' => 18,     // Departemen User
            'G' => 12,     // Urgency
            'H' => 15,     // Teknisi
            'I' => 18,     // Departemen Teknisi
            'J' => 10,     // Status
            'K' => 18,     // Tanggal Selesai
        ];
    }

    /**
     * Styling
     */
    public function styles(Worksheet $sheet)
    {
        // ============================================
        // ROW 1: Judul Laporan
        // ============================================
        $sheet->mergeCells('A1:K1');
        $sheet->setCellValue('A1', 'LAPORAN HELPDESK SYSTEM - TIKET CLOSED');
        $sheet->getStyle('A1')->applyFromArray([
            'font' => [
                'name' => 'Calibri',
                'size' => 16,
                'bold' => true,
                'color' => ['rgb' => 'FFFFFF'],
            ],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['rgb' => '1F3A70'], // Navy blue
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER,
            ],
        ]);
        // ✅ PERBAIKAN: Ganti getRowDimensions() jadi getRowDimension(rowNumber)
        $sheet->getRowDimension(1)->setRowHeight(25);

        // ============================================
        // ROW 3: Periode Tanggal
        // ============================================
        $periodeText = '';
        if ($this->startDate && $this->endDate) {
            $periodeText = 'Periode: ' . date('d/m/Y', strtotime($this->startDate)) . ' - ' . date('d/m/Y', strtotime($this->endDate));
        }
        
        $sheet->mergeCells('A3:K3');
        $sheet->setCellValue('A3', $periodeText);
        $sheet->getStyle('A3')->applyFromArray([
            'font' => [
                'name' => 'Calibri',
                'size' => 11,
                'italic' => true,
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER,
            ],
        ]);
        // ✅ PERBAIKAN
        $sheet->getRowDimension(3)->setRowHeight(18);

        // ============================================
        // ROW 5: Header Tabel
        // ============================================
        $headerRow = 5;
        $sheet->getStyle("A{$headerRow}:K{$headerRow}")->applyFromArray([
            'font' => [
                'name' => 'Calibri',
                'size' => 11,
                'bold' => true,
                'color' => ['rgb' => 'FFFFFF'],
            ],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['rgb' => '2F5496'], // Darker blue
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER,
                'wrapText' => true,
            ],
            'border' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => ['rgb' => '000000'],
                ],
            ],
        ]);
        // ✅ PERBAIKAN
        $sheet->getRowDimension($headerRow)->setRowHeight(25);

        // ============================================
        // ROW 6+: Data Rows
        // ============================================
        $dataStartRow = 6;
        $dataEndRow = $dataStartRow + count($this->data) - 1;

        for ($row = $dataStartRow; $row <= $dataEndRow; $row++) {
            $sheet->getStyle("A{$row}:K{$row}")->applyFromArray([
                'font' => [
                    'name' => 'Calibri',
                    'size' => 10,
                ],
                'alignment' => [
                    'horizontal' => Alignment::HORIZONTAL_LEFT,
                    'vertical' => Alignment::VERTICAL_CENTER,
                    'wrapText' => true,
                ],
                'border' => [
                    'allBorders' => [
                        'borderStyle' => Border::BORDER_THIN,
                        'color' => ['rgb' => 'CCCCCC'],
                    ],
                ],
            ]);

            // Alternating row colors
            if (($row - $dataStartRow) % 2 == 0) {
                $sheet->getStyle("A{$row}:K{$row}")->getFill()->setFillType(Fill::FILL_SOLID)->setStartColor(['rgb' => 'F2F2F2']);
            }

            // ✅ PERBAIKAN
            $sheet->getRowDimension($row)->setRowHeight(20);
        }

        // ============================================
        // Format Kolom Tertentu
        // ============================================
        // Kolom No (Center)
        $sheet->getStyle("A{$dataStartRow}:A{$dataEndRow}")->applyFromArray([
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
            ],
        ]);

        // Kolom Status (Center)
        $sheet->getStyle("J{$dataStartRow}:J{$dataEndRow}")->applyFromArray([
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
            ],
        ]);

        // Freeze panes (freeze header di row 5)
        $sheet->freezePane('A6');

        return [];
    }
}
