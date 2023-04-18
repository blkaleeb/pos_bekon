<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Penjualan extends Model
{
    use HasFactory;

    protected $table = 'penjualan';
    protected $primaryKey = 'id_penjualan';
    protected $guarded = [];

    public static function jenis_pembayaran(){
        return [
            1 => 'Transfer',
            2 => 'Cash',
            2 => 'EDC'
        ];
    }
    public static function statuses(){
        return [
            1 => 'Belum lunas',
            2 => 'Lunas'
        ];
    }

    public function member()
    {
        return $this->hasOne(Member::class, 'id_member', 'id_member');
    }

    public function user()
    {
        return $this->hasOne(User::class, 'id', 'id_user');
    }
    public function sales()
    {
        return $this->hasOne(SalesMember::class, 'id', 'id_salesmember');
    }
}
