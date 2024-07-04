<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class lampiran extends Model
{
    use HasFactory;
    protected $table="lampirans";


    protected $fillable = [
        'lampiran',
        'surat_id',
    ];

    public function surat(){
        return $this->belongsTo('App\Models\surat, surat_id');
    }
}
