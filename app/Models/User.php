<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'path_image'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    public function bidang(){
        return $this->belongsTo('App\Models\bidang', 'bidang_id');
    }

    public function jabatan(){
        return $this->belongsTo('App\Models\jabatan', 'jabatan_id');
    }

    public function hak_akses(){
        return $this->belongsTo('App\Models\hakakses', 'hak_akses_id');
    }

    public function nama_pejabat(){
        return $this->hasMany('App\Models\surat');
    }
    public function pembuat_surat(){
        return $this->hasMany('App\Models\surat');
    }
}
