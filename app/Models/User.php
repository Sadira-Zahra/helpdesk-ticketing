<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'nik',
        'username',
        'nama',
        'email',
        'no_telepon',
        'password',
        'photo',
        'role',
        'departemen_id',
    ];

    /**
     * The attributes that should be hidden for serialization.
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Relasi ke Departemen
     */
    public function departemen()
    {
        return $this->belongsTo(Departemen::class, 'departemen_id');
    }

    /**
     * Relasi tiket yang dibuat user (sebagai pembuat tiket)
     */
    public function tikets()
    {
        return $this->hasMany(Tiket::class, 'user_id');
    }

    /**
     * Relasi tiket yang dikerjakan teknisi (sebagai teknisi)
     */
    public function tiketsDikerjakan()
    {
        return $this->hasMany(Tiket::class, 'teknisi_id');
    }

    /**
     * Scope: Filter by role
     */
    public function scopeByRole($query, $role)
    {
        return $query->where('role', $role);
    }

    /**
     * Scope: Ambil user dengan role teknisi
     */
    public function scopeTeknisi($query)
    {
        return $query->where('role', 'teknisi');
    }

    /**
     * Scope: Ambil user dengan role user
     */
    public function scopeRegularUser($query)
    {
        return $query->where('role', 'user');
    }

    /**
     * Scope: Ambil user dengan role admin atau administrator
     */
    public function scopeAdmin($query)
    {
        return $query->whereIn('role', ['admin', 'administrator']);
    }

    /**
     * Scope: Ambil user dengan role administrator
     */
    public function scopeAdministrator($query)
    {
        return $query->where('role', 'administrator');
    }

    /**
     * Check if user is administrator
     */
    public function isAdministrator()
    {
        return $this->role === 'administrator';
    }

    /**
     * Check if user is admin
     */
    public function isAdmin()
    {
        return $this->role === 'admin';
    }

    /**
     * Check if user is teknisi
     */
    public function isTeknisi()
    {
        return $this->role === 'teknisi';
    }

    /**
     * Check if user is regular user
     */
    public function isUser()
    {
        return $this->role === 'user';
    }

    /**
     * Check if user has admin privileges (admin or administrator)
     */
    public function hasAdminPrivileges()
    {
        return in_array($this->role, ['admin', 'administrator']);
    }

    /**
     * Get user's full name with NIK
     */
    public function getFullNameAttribute()
    {
        return $this->nama . ' (' . $this->nik . ')';
    }

    /**
     * Get user's role label
     */
    public function getRoleLabelAttribute()
    {
        return match($this->role) {
            'administrator' => 'Administrator',
            'admin' => 'Admin',
            'teknisi' => 'Teknisi',
            'user' => 'User',
            default => ucfirst($this->role),
        };
    }

    /**
     * Get user's photo URL
     */
    public function getPhotoUrlAttribute()
    {
        if ($this->photo) {
            return asset('storage/' . $this->photo);
        }
        
        // Default avatar with initial
        $initial = strtoupper(substr($this->nama ?? 'U', 0, 1));
        return 'https://via.placeholder.com/150/667eea/ffffff?text=' . urlencode($initial);
    }

    /**
     * Get user's departemen name (null-safe)
     */
    public function getDepartemenNameAttribute()
    {
        return $this->departemen ? $this->departemen->nama_departemen : '-';
    }

    // TAMBAH RELASI INI
    public function assignedTickets()
    {
        return $this->hasMany(Tiket::class, 'teknisi_id');
    }
}
