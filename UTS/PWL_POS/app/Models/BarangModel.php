<?php

namespace App\Models;

use App\Models\KategoriModel;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\Auth;

class BarangModel extends Model
{
    use HasFactory;

    protected $table = 'm_barang';
    protected $primaryKey = 'barang_id';

    protected $fillable = [
        'kategori_id',
        'barang_kode',
        'barang_nama',
        'harga_beli',
        'harga_jual',
    ];

    protected static function booted()
    {
        static::created(function ($barang) {
            // Buat entri stok awal otomatis
            StokModel::create([
                'barang_id'    => $barang->barang_id,
                'user_id'      => Auth::id() ?? 1, // fallback ke user ID 1 jika tidak login
                'stok_tanggal' => now(),
                'stok_jumlah'  => 0, // stok awal bisa 0 atau nilai default lain
            ]);
        });
    }

    public function kategori()
    {
        return $this->belongsTo(KategoriModel::class, 'kategori_id', 'kategori_id');
    }

    public function stok()
    {
        return $this->hasMany(StokModel::class, 'barang_id', 'barang_id');
    }
}