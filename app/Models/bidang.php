<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class bidang extends Model
{
    use HasFactory;
    
    protected $table="bidangs";


    protected $fillable = [
        'name',
    ];

    public function user(){
        return $this->hasMany('App\Models\User');
    }

    public function surat(){
        return $this->hasMany('App\Models\surat');
    }
}
