<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Karyawan extends Model
{
    use SoftDeletes, HasFactory;
    
    protected $guarded = [
        'id',
    ];
    protected $dates = ['deleted_at'];

    public function users(){
        return $this->belongsTo(User::class, 'id_user');
    }

    public function reservasis(){
        return $this->hasOne(Reservasi::class, 'id_karyawan', 'id');
    }
}
