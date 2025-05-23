<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rest extends Model
{
    use HasFactory;

    protected $fillable=['work_id','rest_start','rest_end'];

    public function getTotalRestTime($id){
        $work = Work::with('rests')->find($id);
        $rests = $work->rests->get();
        $totalRestTime = $rests->sum(function($rest){
            return $rest->rest_end->diffInMinutes($rest->rest_start);
        });
    }
}
