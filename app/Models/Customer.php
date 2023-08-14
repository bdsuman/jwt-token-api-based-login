<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    protected $fillable = ['name','email','mobile','user_id'];

    public function user(){
        return $this->belongsTo(User::class);
    }
}
