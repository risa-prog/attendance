<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RestCorrection extends Model
{
    use HasFactory;
    protected $fillable = [
        'work_id',
        'rest_id',
        'rest_start','rest_end',
    ];

    public function work(){
        return $this->belongsTo('App\Models\Work');
    }
}
