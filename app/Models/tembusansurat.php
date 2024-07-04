<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class tembusansurat extends Model
{
    use HasFactory;
    protected $table="tembusansurats";


    protected $fillable = [
        'tembusan_surat',
        'surat_id',
    ];

    public function surat(){
        return $this->belongsTo('App\Models\surat, surat_id');
    }
}
