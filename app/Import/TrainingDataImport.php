<?php

namespace App\Imports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class TrainingDataImport implements ToCollection, WithHeadingRow
{
    public function collection(Collection $rows)
    {
        foreach ($rows as $row) {
            // Parse tanggal
            $tanggal = $this->parseDate($row['tanggal_problem'] ?? $row['tanggal']);
            
            // Ambil data
            $deskripsi = $row['deskripsi_problem'] ?? $row['deskripsi'] ?? '';
            $kategori = $row['kategori'] ?? 'Umum';
            $sla = (int) ($row['sla_target_hrs'] ?? $row['sla_target'] ?? $row['sla'] ?? 48);
            $actual = (int) ($row['actual_hrs'] ?? $row['actual'] ?? 48);
            
            // Mapping SLA ke Urgency Level
            $urgency = $this->mapSLAtoUrgency($sla);
            
            // Insert ke database
            DB::table('tiket_training')->insert([
                'tanggal_problem' => $tanggal,
                'deskripsi_problem' => $deskripsi,
                'kategori' => $kategori,
                'sla_target_hrs' => $sla,
                'actual_hrs' => $actual,
                'urgency_level' => $urgency,
                'created_at' => now(),
                'updated_at' => now()
            ]);
        }
    }
    
    /**
     * Parse tanggal dari berbagai format
     */
    private function parseDate($dateValue)
    {
        if (empty($dateValue)) {
            return now()->format('Y-m-d');
        }
        
        // Jika sudah format Carbon/DateTime
        if ($dateValue instanceof \DateTime) {
            return $dateValue->format('Y-m-d');
        }
        
        // Jika Excel serial number
        if (is_numeric($dateValue)) {
            return Carbon::createFromFormat('Y-m-d', '1900-01-01')
                ->addDays($dateValue - 2)
                ->format('Y-m-d');
        }
        
        // Parse string tanggal
        try {
            return Carbon::parse($dateValue)->format('Y-m-d');
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
}
