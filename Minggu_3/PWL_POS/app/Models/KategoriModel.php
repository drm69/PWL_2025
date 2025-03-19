<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class KategoriModel extends Model
{
    use HasFactory;

    protected $table = 'm_kategori';
    protected $primaryKey = 'kategori_id';

    protected $fillable = ['nama_kategori'];

    // Relasi ke BarangModel (1 Kategori memiliki banyak Barang)
    public function barang(): HasMany
    {
        return $this->hasMany(BarangModel::class, 'kategori_id', 'kategori_id');
    }
}
