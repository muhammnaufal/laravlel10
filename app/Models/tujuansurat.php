<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class tujuansurat extends Model
{
    use HasFactory;
    protected $table="tujuansurats";


    protected $fillable = [
        'tujuan_surat',
        'surat_id',
    ];

    public function surat(){
        return $this->belongsTo('App\Models\surat, surat_id');
    }
}
