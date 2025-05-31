<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use Carbon\Carbon;
use App\Models\Work;
use App\Models\Rest;

class TimestampController extends Controller
{
    public function workStart(){
        $user = Auth::user();
        $date = Carbon::now()->format('Y-m-d');
        
        $work_start = Carbon::now()->format('H:i');
        
        Work::create([
            'user_id' => $user->id,
            'date' => $date,
            'work_start' => $work_start,
            'status' => '1',
        ]);

        return redirect('/attendance');
    }

    public function workEnd(){
        $user = Auth::user();
        $date = Carbon::now()->format('Y-m-d');
        
        $work_end = Carbon::now()->format('H:i');

        $work = Work::where([
             ['user_id','=',$user->id] ,
             ['date','=',$date]
         ])->first();

       $work->work_end = $work_end;
       $work->save();

       Work::where([
            ['user_id','=',$user->id] ,
            ['date','=',$date]
         ])->update(['status' => '3']);

       return redirect('/attendance');
    }

    public function restStart(){
        $user = Auth::user();
        $date = Carbon::now()->format('Y-m-d');
        $work = Work::where([
             ['user_id','=',$user->id],
             ['date','=',$date]
         ])->first();

        $rest_start = Carbon::now()->format('H:i');

        $rest = Rest::create([
            'work_id' => $work->id,
            'rest_start' => $rest_start,
        ]);

        Work::where([
             ['user_id','=',$user->id] ,
             ['date','=',$date]
         ])->update(['status' => '2']);

       return redirect('/attendance');

    }

    public function restEnd(){
        $user = Auth::user();
        $date = Carbon::today()->format('Y-m-d');
        $work = Work::where([
             ['user_id','=',$user->id] ,
             ['date','=',$date]
         ])->first();

        $rest = Rest::where([
            'work_id' => $work->id,
            'rest_end' => null,
         ])->first();

        $rest_end = Carbon::now()->format('H:i');

        $rest->rest_end = $rest_end;
        $rest->save();

        Work::where([
             ['user_id','=',$user->id] ,
             ['date','=',$date]
         ])->update(['status' => '1']);

        return redirect('/attendance');
    }

}
