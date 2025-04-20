<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class StokModel extends Model
{
    use HasFactory;

    protected $table = 't_stok';
    
    protected $primaryKey = 'stok_id';

    protected $fillable = ['barang_id', 'user_id', 'stok_tanggal', 'stok_jumlah'];

    protected $casts = [
        'stok_tanggal' => 'datetime',
    ];

    public function barang(): BelongsTo {
        return $this->belongsTo(BarangModel::class, 'barang_id', 'barang_id');
    }

    public function user(): BelongsTo {
        return $this->belongsTo(UserModel::class, 'user_id', 'user_id');
    }

    public function getRoleName(): string
    {
        return $this->barang->barang_nama;
    }

    /**
     * Cek apakah user memiliki role tertentu
     */
    public function hasRole($role): bool
    {
        return $this->barang->barang_kode == $role;
    }

    /**
     * Mendapatkan kode role
     */
    public function getRole()
    {
        return $this->barang->barang_kode;
    }
}