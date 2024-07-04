<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class surat extends Model
{
    use HasFactory;

    use HasFactory;
    protected $table="surats";


    protected $fillable = [
        "tanggal_surat",
        "nomor_surat",
        "keterangan_lampiran",
        "perihal_surat",
        "alamat_instansi/pejabat",
        "rincian_pelaksanaan_penugasan",
        "beban anggaran",
        "nama pejabat",
        "e2",
        "e3",
        "e4",
        "pembuat_surat",
        'pdf',
    ];

    public function tujuan_surat(){
        return $this->hasMany('App\Models\tujuansurat');
    }
    public function dasar_acuan_surat(){
        return $this->hasMany('App\Models\dasaracuansurat');
    }
    public function tembusan_surat(){
        return $this->hasMany('App\Models\tembusansurat');
    }
    public function lampiran(){
        return $this->hasMany('App\Models\lampiran');
    }
    public function riwayat_surat(){
        return $this->hasMany('App\Models\RiwayatSurat');
    }
    
    public function nama_pejabat(){
        return $this->belongsTo('App\Models\User', 'nama_pejabat');
    }
    public function nama_pembuat(){
        return $this->belongsTo('App\Models\User', 'pembuat_surat');
    }
    public function bidang(){
        return $this->belongsTo('App\Models\bidang', 'bidang_id');
    }
    public function beban_anggaran(){
        return $this->belongsTo('App\Models\BebanAnggaran', 'beban_anggaran_id');
    }
}
