<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PenjualanModel extends Model
{
    use HasFactory;
    protected $table = 't_penjualan';
    protected  $primaryKey = 'penjualan_id';

    protected $fillable = ['user_id', 'pembeli', 'penjualan_kode', 'penjualan_tanggal'];

    public function user(): BelongsTo {
        return $this->belongsTo(UserModel::class, 'user_id', 'user_id');
    }

    public function penjualan_detail(): BelongsTo {
        return $this->belongsTo(PenjualanDetailModel::class, 'penjualan_id', 'penjualan_id');
    }

}
