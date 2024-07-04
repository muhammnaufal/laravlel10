<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RiwayatSurat extends Model
{
    use HasFactory;
    protected $table="riwayat_surats";


    protected $fillable = [
        'riwayat',
        'surat_id',
    ];

    public function surat(){
        return $this->belongsTo('App\Models\surat, surat_id');
    }
}
