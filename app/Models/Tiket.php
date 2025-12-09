<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tiket extends Model
{
    protected $table = 'tiket';
    
    protected $fillable = [
        'user_id',
        'departemen_id',
        'nomor',
        'tanggal',
        'judul',
        'keterangan',
        'urgency_id',
        'gambar',
        'status',
        'tanggal_selesai',
        'teknisi_id',
        'catatan',
        'solusi',
        
        // ===== TAMBAHAN BARU =====
        'tipe_masalah',
        'kata_kunci',
        'dept_terdampak',
        'recommended_urgency_id',
        'confidence_score'
        // ===== AKHIR TAMBAHAN =====
    ];

    // TAMBAHKAN INI - Casting tanggal ke Carbon
     // ✅ PENTING: Cast dates dengan nullable handling
    protected $casts = [
        'tanggal' => 'datetime',
        'tanggal_selesai' => 'datetime',
        'confidence_score' => 'float'
    ];
    
    // Relasi lama (jangan diubah)
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    
    public function departemen()
    {
        return $this->belongsTo(Departemen::class);
    }
    
    public function urgency()
    {
        return $this->belongsTo(Urgency::class);
    }
    
    public function teknisi()
    {
        return $this->belongsTo(User::class, 'teknisi_id');
    }
    
    // ===== TAMBAHAN BARU: Relasi untuk rekomendasi =====
    public function recommendedUrgency()
    {
        return $this->belongsTo(Urgency::class, 'recommended_urgency_id');
    }
    // ===== AKHIR TAMBAHAN =====

     // TAMBAH RELASI INI jika belum ada
    public function assignedTickets()
    {
        return $this->hasMany(Tiket::class, 'teknisi_id', 'id');
    }

    
}
