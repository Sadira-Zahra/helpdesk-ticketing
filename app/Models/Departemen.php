<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Departemen extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'departemen';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'nama_departemen',
    ];

    /**
     * Relasi ke users
     */
    public function users()
    {
        return $this->hasMany(User::class, 'departemen_id');
    }

    /**
     * Relasi ke tiket
     */
    public function tiket()
    {
        return $this->hasMany(Tiket::class, 'departemen_id');
    }
}
