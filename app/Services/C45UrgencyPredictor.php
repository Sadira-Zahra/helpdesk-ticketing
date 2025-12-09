<?php

namespace App\Services;

use App\Models\Urgency;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class C45UrgencyPredictor
{
    private $tree;
    private $rules;
    private $maxDepth = 5;
    private $minSamplesLeaf = 2;

    public function __construct()
    {
        // Load model dari database jika ada
        $this->loadModel();
    }

    /**
     * Load Model dari Database
     */
    private function loadModel()
    {
        try {
            $model = DB::table('c45_model')->where('id', 1)->first();
            
            if ($model) {
                $this->tree = json_decode($model->tree_json, true);
                $this->rules = json_decode($model->rules_json, true);
            }
        } catch (\Exception $e) {
            // Jika tabel tidak ada atau kosong, set default
            $this->tree = null;
            $this->rules = [];
        }
    }

    /**
     * PREDICT - Prediksi urgency berdasarkan keterangan
     * 
     * @param string $keterangan - Deskripsi masalah
     * @param string $departemen - Nama departemen
     * @return array
     */
    public function predict($keterangan, $departemen = '')
    {
        try {
            // ========================================
            // AUTO-DETECT TIPE MASALAH & DEPT TERDAMPAK
            // ========================================
            $tipeMasalah = $this->detectTipeMasalah($keterangan);
            $deptTerdampak = $this->detectDepartemenTerdampak($keterangan, $departemen);

            $keterangan = strtolower($keterangan);
            
            // ========================================
            // AMBIL URGENCY DARI DATABASE (DINAMIS)
            // ========================================
            $urgencies = Urgency::orderBy('jam', 'asc')->get();
            
            if ($urgencies->isEmpty()) {
                Log::warning('Tabel urgency kosong!');
                return $this->getDefaultPrediction($departemen);
            }
            
            // Ambil urgency berdasarkan urutan (index)
            // Index 0 = Urgency paling cepat (jam terkecil)
            // Index terakhir = Urgency paling lambat (jam terbesar)
            $highUrgency = $urgencies->first();        // Urgency pertama (jam terkecil)
            $lowUrgency = $urgencies->last();          // Urgency terakhir (jam terbesar)
            
            // Jika ada 3+ urgency, ambil yang tengah sebagai medium
            $mediumUrgency = $urgencies->count() >= 3 
                ? $urgencies->skip(1)->first() 
                : $urgencies->skip(1)->first() ?? $highUrgency;
            
            // ========================================
            // KATA KUNCI KLASIFIKASI
            // ========================================
            $urgentKeywords = [
                'urgent', 'mendesak', 'segera', 'darurat', 'emergency',
                'down', 'mati', 'rusak parah', 'error fatal', 'tidak bisa',
                'critical', 'kritis', 'penting sekali', 'harus segera',
                'server down', 'sistem down', 'production', 'gagal total'
            ];
            
            $mediumKeywords = [
                'lambat', 'slow', 'lemot', 'butuh', 'perlu perbaikan',
                'request', 'minta bantuan', 'tolong', 'help', 'mohon',
                'tidak lancar', 'kurang optimal', 'error', 'masalah'
            ];
            
            $lowKeywords = [
                'saran', 'usul', 'request fitur', 'tambahan', 'optional',
                'jika bisa', 'enhancement', 'improvement', 'pertanyaan',
                'informasi', 'tanya', 'konsultasi'
            ];
            
            // ========================================
            // HITUNG KATA KUNCI YANG COCOK
            // ========================================
            $urgentCount = 0;
            $mediumCount = 0;
            $lowCount = 0;
            $foundKeywords = [];
            
            foreach ($urgentKeywords as $keyword) {
                if (strpos($keterangan, $keyword) !== false) {
                    $urgentCount++;
                    $foundKeywords[] = $keyword;
                }
            }
            
            foreach ($mediumKeywords as $keyword) {
                if (strpos($keterangan, $keyword) !== false) {
                    $mediumCount++;
                    if (!in_array($keyword, $foundKeywords)) {
                        $foundKeywords[] = $keyword;
                    }
                }
            }
            
            foreach ($lowKeywords as $keyword) {
                if (strpos($keterangan, $keyword) !== false) {
                    $lowCount++;
                    if (!in_array($keyword, $foundKeywords)) {
                        $foundKeywords[] = $keyword;
                    }
                }
            }
            
            // ========================================
            // TENTUKAN URGENCY BERDASARKAN SCORING
            // ========================================
            $selectedUrgency = null;
            $confidence = 0.5;
            $tipeMasalah = 'Normal';
            
            if ($urgentCount >= 2) {
                // URGENT - Ada 2+ kata kunci urgent
                $selectedUrgency = $highUrgency;
                $confidence = min(0.90 + ($urgentCount * 0.03), 0.99);
                $tipeMasalah = 'Critical';
                
            } elseif ($urgentCount >= 1) {
                // URGENT - Ada 1 kata kunci urgent
                $selectedUrgency = $highUrgency;
                $confidence = 0.85;
                $tipeMasalah = 'Critical';
                
            } elseif ($mediumCount >= 2) {
                // MEDIUM - Ada 2+ kata kunci medium
                $selectedUrgency = $mediumUrgency;
                $confidence = min(0.75 + ($mediumCount * 0.03), 0.88);
                // $tipeMasalah sudah di-set di awal (auto-detect)
                
            } elseif ($mediumCount >= 1) {
                // MEDIUM - Ada 1 kata kunci medium
                $selectedUrgency = $mediumUrgency;
                $confidence = 0.70;
                // $tipeMasalah sudah di-set di awal (auto-detect)
                
            } elseif ($lowCount >= 1) {
                // LOW - Ada kata kunci low priority
                $selectedUrgency = $lowUrgency;
                $confidence = 0.65;
                $tipeMasalah = 'Minor';
                
            } else {
                // DEFAULT - Tidak ada kata kunci yang cocok
                // Gunakan medium sebagai default
                $selectedUrgency = $mediumUrgency;
                $confidence = 0.60;
                // $tipeMasalah sudah di-set di awal (auto-detect)
            }
            
            // ========================================
            // RETURN PREDICTION RESULT
            // ========================================
            return [
                'tipe_masalah' => $tipeMasalah,
                'kata_kunci' => !empty($foundKeywords) 
                    ? implode(', ', array_slice($foundKeywords, 0, 5))
                    : 'Tidak ada kata kunci khusus',
                'dept_terdampak' => $deptTerdampak,
                'recommended_urgency_id' => $selectedUrgency->id,
                'confidence_score' => $confidence,
                'recommended_urgency' => $selectedUrgency->urgency
            ];
            
        } catch (\Exception $e) {
            Log::error('C4.5 Prediction Error: ' . $e->getMessage());
            return $this->getDefaultPrediction($departemen);
        }
    }

    /**
     * Get Default Prediction (Fallback)
     */
    private function getDefaultPrediction($departemen)
    {
        return [
            'tipe_masalah' => 'Hardware', // Default fallback
            'kata_kunci' => 'Tidak ada analisis',
            'dept_terdampak' => $deptTerdampak,
            'recommended_urgency_id' => null,
            'confidence_score' => null,
            'recommended_urgency' => 'Menunggu Review Admin'
        ];
    }

    /**
     * TRAINING - Build C4.5 Model dari data historis
     */
    public function train()
    {
        try {
            // Get training data dari tiket yang sudah selesai
            $trainingData = DB::table('tiket')
                ->join('urgency', 'tiket.urgency_id', '=', 'urgency.id')
                ->whereIn('tiket.status', ['finish', 'closed'])
                ->whereNotNull('tiket.urgency_id')
                ->select(
                    'tiket.id',
                    'tiket.tipe_masalah',
                    'tiket.dept_terdampak',
                    'urgency.urgency as urgency_level'
                )
                ->get();

            if ($trainingData->count() < 5) {
                return [
                    'success' => false,
                    'message' => 'Data training tidak cukup. Minimal 5 tiket selesai diperlukan.',
                    'data_count' => $trainingData->count()
                ];
            }

            // Build decision tree
            $this->tree = $this->buildTree($trainingData, 0);
            
            // Generate rules
            $this->rules = $this->extractRules($this->tree);
            
            // Calculate accuracy
            $accuracy = $this->calculateAccuracy($trainingData);
            
            // Save to database
            $this->saveModel($accuracy, $trainingData->count());

            return [
                'success' => true,
                'accuracy' => round($accuracy, 2),
                'data_count' => $trainingData->count(),
                'rules_count' => count($this->rules),
                'message' => 'Model C4.5 berhasil di-train!'
            ];
            
        } catch (\Exception $e) {
            Log::error('C4.5 Training Error: ' . $e->getMessage());
            
            return [
                'success' => false,
                'message' => 'Gagal melakukan training: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Build Decision Tree dengan Depth Limit
     */
    private function buildTree($data, $depth)
    {
        // Stop condition: max depth atau data terlalu sedikit
        if ($depth >= $this->maxDepth || $data->count() <= $this->minSamplesLeaf) {
            return $this->createLeaf($data);
        }
        
        // Cek apakah semua data punya class yang sama
        $classes = $data->pluck('urgency_level')->toArray();
        if (count(array_unique($classes)) === 1) {
            return ['type' => 'leaf', 'class' => $classes[0]];
        }
        
        // Pilih best attribute
        $bestAttribute = $this->selectBestAttribute($data);
        if (!$bestAttribute) {
            return $this->createLeaf($data);
        }
        
        // Split data berdasarkan attribute
        $branches = [];
        $splits = $this->splitData($data, $bestAttribute);
        
        foreach ($splits as $value => $subset) {
            if (count($subset) > 0) {
                $branches[$value] = $this->buildTree(collect($subset), $depth + 1);
            }
        }
        
        // Jika hanya 1 cabang, langsung return leaf
        if (count($branches) <= 1) {
            return $this->createLeaf($data);
        }
        
        return [
            'type' => 'node',
            'attribute' => $bestAttribute,
            'branches' => $branches
        ];
    }

    /**
     * Create Leaf Node
     */
    private function createLeaf($data)
    {
        $classes = $data->pluck('urgency_level')->toArray();
        $classCount = array_count_values($classes);
        arsort($classCount);
        
        return [
            'type' => 'leaf',
            'class' => array_key_first($classCount),
            'count' => count($data)
        ];
    }

    /**
     * Select Best Attribute menggunakan Information Gain
     */
    private function selectBestAttribute($data)
    {
        $attributes = ['tipe_masalah', 'dept_terdampak'];
        $baseEntropy = $this->calculateEntropy($data);
        
        $bestGain = 0;
        $bestAttribute = null;
        
        foreach ($attributes as $attribute) {
            $gain = $this->calculateInformationGain($data, $attribute, $baseEntropy);
            if ($gain > $bestGain) {
                $bestGain = $gain;
                $bestAttribute = $attribute;
            }
        }
        
        return $bestAttribute;
    }

    /**
     * Calculate Entropy
     */
    private function calculateEntropy($data)
    {
        $total = $data->count();
        if ($total == 0) return 0;
        
        $classes = $data->pluck('urgency_level')->toArray();
        $classCount = array_count_values($classes);
        
        $entropy = 0;
        foreach ($classCount as $count) {
            $probability = $count / $total;
            if ($probability > 0) {
                $entropy -= $probability * log($probability, 2);
            }
        }
        
        return $entropy;
    }

    /**
     * Calculate Information Gain
     */
    private function calculateInformationGain($data, $attribute, $baseEntropy)
    {
        $total = $data->count();
        $splits = $this->splitData($data, $attribute);
        
        $weightedEntropy = 0;
        foreach ($splits as $subset) {
            $weight = count($subset) / $total;
            $weightedEntropy += $weight * $this->calculateEntropy(collect($subset));
        }
        
        return $baseEntropy - $weightedEntropy;
    }

    /**
     * Split Data by Attribute
     */
    private function splitData($data, $attribute)
    {
        $splits = [];
        
        foreach ($data as $row) {
            $value = $row->{$attribute} ?? 'Unknown';
            if (!isset($splits[$value])) {
                $splits[$value] = [];
            }
            $splits[$value][] = $row;
        }
        
        return $splits;
    }

    /**
     * Extract Rules dari Tree
     */
    private function extractRules($node, $conditions = [])
    {
        if ($node['type'] === 'leaf') {
            return [[
                'conditions' => $conditions,
                'conclusion' => $node['class']
            ]];
        }
        
        $rules = [];
        foreach ($node['branches'] as $value => $branch) {
            $newConditions = $conditions;
            $newConditions[] = [
                'attribute' => $node['attribute'],
                'value' => $value
            ];
            $branchRules = $this->extractRules($branch, $newConditions);
            $rules = array_merge($rules, $branchRules);
        }
        
        return $rules;
    }

    /**
     * Calculate Accuracy
     */
    private function calculateAccuracy($data)
    {
        $correct = 0;
        
        foreach ($data as $row) {
            $predicted = $this->predictFromTree($row);
            if ($predicted === $row->urgency_level) {
                $correct++;
            }
        }
        
        return $data->count() > 0 ? ($correct / $data->count()) * 100 : 0;
    }

    /**
     * Predict dari Tree (untuk training accuracy)
     */
    private function predictFromTree($instance)
    {
        if (!$this->tree) return null;
        return $this->traverseTree($this->tree, $instance);
    }

    /**
     * Traverse Tree untuk Prediction
     */
    private function traverseTree($node, $instance)
    {
        if ($node['type'] === 'leaf') {
            return $node['class'];
        }
        
        $attributeValue = $instance->{$node['attribute']} ?? 'Unknown';
        
        if (isset($node['branches'][$attributeValue])) {
            return $this->traverseTree($node['branches'][$attributeValue], $instance);
        }
        
        // Fallback: return most common class
        return $this->getMostCommonClass($node);
    }

    /**
     * Get Most Common Class dari node
     */
    private function getMostCommonClass($node)
    {
        if ($node['type'] === 'leaf') {
            return $node['class'];
        }
        
        $classes = [];
        foreach ($node['branches'] as $branch) {
            $classes[] = $this->getMostCommonClass($branch);
        }
        
        $classCount = array_count_values($classes);
        arsort($classCount);
        return array_key_first($classCount);
    }

    /**
     * Save Model ke Database
     */
    private function saveModel($accuracy, $dataCount)
    {
        try {
            DB::table('c45_model')->updateOrInsert(
                ['id' => 1],
                [
                    'tree_json' => json_encode($this->tree),
                    'rules_json' => json_encode($this->rules),
                    'accuracy' => $accuracy,
                    'data_count' => $dataCount,
                    'updated_at' => now()
                ]
            );
        } catch (\Exception $e) {
            Log::error('Failed to save C4.5 model: ' . $e->getMessage());
        }
    }

    /**
     * Deteksi departemen terdampak (Produksi atau Non-Produksi)
     * Berdasarkan nama departemen dan keyword di keterangan
     */
    private function detectDepartemenTerdampak($keterangan, $namaDepartemen = '')
    {
        $keterangan = strtolower($keterangan);
        $namaDepartemen = strtoupper($namaDepartemen);

        // Keyword produksi di keterangan
        $produksiKeywords = [
            'asm', 'et', 'ikn', 'machining', 'ppc', 'produksi', 'mtn', 
            'production', 'mesin', 'lantai produksi', 'area produksi'
        ];

        // Cek keyword di keterangan
        foreach ($produksiKeywords as $keyword) {
            if (str_contains($keterangan, $keyword)) {
                return 'Produksi';
            }
        }

        // Mapping departemen ke Produksi/Non-Produksi
        // Departemen produksi
        $deptProduksi = ['PROD', 'ASM', 'ET', 'IKN', 'MACHINING', 'PPC', 'MTN', 'QC', 'IT', 'SERVER', 'FA'];

        // Departemen non-produksi
        $deptNonProduksi = ['GA', 'PUR', 'EHS', 'HR', 'ACCOUNTING', 'FINANCE'];

        // Cek nama departemen
        if (in_array($namaDepartemen, $deptProduksi)) {
            return 'Produksi';
        }

        if (in_array($namaDepartemen, $deptNonProduksi)) {
            return 'Non-Produksi';
        }

        // Default: Non-Produksi (karena IT/Admin biasanya non-produksi)
        return 'Non-Produksi';
    }


    /**
     * Deteksi tipe masalah dari kata kunci
     */
    private function detectTipeMasalah($keterangan)
    {
        $keterangan = strtolower($keterangan);

        // Hardware: PC, Printer, Laptop, Kamera, Jam Digital, Mouse, Keyboard, dll
        if (preg_match('/\b(pc|printer|laptop|kamera|jam digital|mouse|keyboard|monitor|scanner|hardisk|ram|cpu)\b/i', $keterangan)) {
            return 'Hardware';
        }

        // Software: Install, Setting, Software, Windows, Office, Aplikasi, dll
        if (preg_match('/\b(install|setting|software|windows|office|aplikasi|program|update|upgrade)\b/i', $keterangan)) {
            return 'Software';
        }

        // Network: Internet, Jaringan, LAN, Kabel, Switch, WiFi, dll
        if (preg_match('/\b(internet|jaringan|lan|kabel|switch|wifi|koneksi|network|router)\b/i', $keterangan)) {
            return 'Network';
        }

        // Email: Email, Password, Reset, Outlook, Gmail, dll
        if (preg_match('/\b(email|password|reset|outlook|gmail|mail)\b/i', $keterangan)) {
            return 'Email';
        }

        // Default: Hardware
        return 'Hardware';
    }

}
