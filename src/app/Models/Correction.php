<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Correction extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id','work_id','punchIn','punchOut','break_begins','break_ends','note','status'
    ];
    
    public function user(){
        return $this->belongsTo('App\Models\User');
    }

    public function work(){
        return $this->belongsTo('App\Models\Work');
    }
}
