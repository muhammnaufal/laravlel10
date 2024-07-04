<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BebanAnggaran extends Model
{
    use HasFactory;

    protected $table="beban_anggaran";


    protected $fillable = [
        'jenis_lembaga',
        'nama_lembaga',
    ];

    public function surat(){
        return $this->hasMany('App\Models\surat');
    }
}
