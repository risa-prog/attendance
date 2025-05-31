<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Work extends Model
{
    use HasFactory;

    protected $fillable = ['user_id','date','work_start','work_end','status'];

    public function user(){
        return $this->belongsTo('App\Models\User');
    }

    public function rests(){
        return $this->hasMany('App\Models\Rest');
    }

    public function workCorrection(){
        return $this->hasOne('App\Models\workCorrection');
    }

    public function restCorrections(){
        return $this->hasMany('App\Models\restCorrection');
    } 
}
