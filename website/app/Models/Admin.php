<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Admin extends Model
{
    use SoftDeletes, HasFactory;

    protected $guarded = [
        'id',
    ];
    protected $dates = ['deleted_at'];

    public function users(){
        return $this->belongsTo(User::class, 'id_user');
    }
}
