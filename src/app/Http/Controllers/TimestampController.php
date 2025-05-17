<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use Carbon\Carbon;
use App\Models\Work;
use App\Models\Rest;

class TimestampController extends Controller
{
    public function punchIn(){
        $user = Auth::user();
        $date = Carbon::today()->format('Y-m-d');
        
        $start_time = Carbon::now()->format('H:i');
        
        Work::create([
            'user_id' => $user->id,
            'date' => $date,
            'start_time' => $start_time,
            'status' => '1'
        ]);

        return redirect('/attendance');
    }

    public function punchOut(){
        $user = Auth::user();
        $date = Carbon::today()->format('Y-m-d');
        
        $end_time = Carbon::now()->format('H:i');

        $work = Work::where([
             ['user_id','=',$user->id] ,
             ['date','=',$date]
         ])->first();

       $work->end_time = $end_time;
       $work->save();

       Work::where([
             ['user_id','=',$user->id] ,
             ['date','=',$date]
         ])->update(['status' => '3']);

       return redirect('/attendance');
    }

    public function breakBegins(){
        $user = Auth::user();
        $date = Carbon::today()->format('Y-m-d');
        $work = Work::where([
             ['user_id','=',$user->id] ,
             ['date','=',$date]
         ])->first();

        $rest_start = Carbon::now()->format('H:i');

        $rest = Rest::create([
            'user_id' => $user->id,
            'work_id' => $work->id,
            'start_time' => $rest_start,
        ]);

        Work::where([
             ['user_id','=',$user->id] ,
             ['date','=',$date]
         ])->update(['status' => '2']);

       return redirect('/attendance');

    }

    public function breakEnds(){
        $user = Auth::user();
        $date = Carbon::today()->format('Y-m-d');
        $work = Work::where([
             ['user_id','=',$user->id] ,
             ['date','=',$date]
         ])->first();

        $rest = Rest::where([
            'user_id' => $user->id,
            'work_id' => $work->id,
            'end_time' => null,
         ])->first();

        $rest_end = Carbon::now()->format('H:i');

        $rest->end_time = $rest_end;
        $rest->save();

        Work::where([
             ['user_id','=',$user->id] ,
             ['date','=',$date]
         ])->update(['status' => '1']);

        return redirect('/attendance');
    }

}
