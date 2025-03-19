<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserModel extends Model
{
    use HasFactory;

    protected $table = 'm_user'; // Nama tabel
    protected $primaryKey = 'user_id'; // Primary key
    public $timestamps = true; // Jika tabel memiliki created_at & updated_at

    protected $fillable = ['username', 'nama', 'password', 'level_id'];

    // Relasi ke LevelModel
    public function level(): BelongsTo
    {
        return $this->belongsTo(LevelModel::class, 'level_id', 'level_id');
    }
}
