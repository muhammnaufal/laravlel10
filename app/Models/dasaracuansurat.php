<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class dasaracuansurat extends Model
{
    use HasFactory;
    protected $table="dasaracuansurats";


    protected $fillable = [
        'dasar_acuan_surat',
        'surat_id',
    ];

    public function surat(){
        return $this->belongsTo('App\Models\surat, surat_id');
    }
}
