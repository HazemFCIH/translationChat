<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Favorit extends Model
{
    use HasFactory;
    protected $guarded = ['id'];

    public function user(){
        return $this->belongsTo(User::class,'user','id');
    }

    public function favorite(){
        return $this->belongsTo(User::class,'favorite_person_id','id');
    }
}
