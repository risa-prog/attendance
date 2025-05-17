<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use Carbon\Carbon;
use App\Models\Work;
use App\Models\Correction;

class AttendanceController extends Controller
{
    public function showAttendance(){
        $now = Carbon::now();
        $today = $now->isoFormat('YYYY年MM月DD日(ddd)');
        $time = Carbon::now()->format('H:i');
        $date = $now->format('Y-m-d');

        $user = Auth::user();
        $work = Work::where([
            ['user_id','=',$user->id] ,
            ['date','=',$date]
        ])->first();
        return view('attendance.timestamp',compact('today','time','work'));
    }

     public function showAttendanceDetail($id){
        if($id === 'list'){
            $user = Auth::user();
        $month = Carbon::today()->format('Y/m');
        $date = Carbon::now()->format('Y-m-d'); 
        
        $from = date('Y-m-01');
        $to = date('Y-m-t'); 
        $works = Work::where('user_id',$user->id)->whereBetween('date',[$from, $to])->get();

        return view('attendance.list',compact('works','month','date'));

        }else{
            $work = Work::find($id);
            return view('attendance/detail',compact('work'));
        }
     }

    public function showCorrectionList(){
        $user = Auth::user();
        $corrections = Correction::where('user_id',$user->id)->get();
        
        return view('attendance.correction',compact('corrections'));
    }

    public function showLastMonth(Request $request){
        
        $request_date = $request->date;
        $startOfPreviousMonth = Carbon::parse($request_date)->subMonth()->startOfMonth();
        $endOfPreviousMonth = Carbon::parse($request_date)->subMonth()->endOfMonth();
        
        $works = Work::whereBetween('date',[$startOfPreviousMonth,$endOfPreviousMonth])->get();
        
        $month = $startOfPreviousMonth->format('Y/m');

        $date = $startOfPreviousMonth->format('Y-m-d');

        return view('attendance.list',compact('works','month','date'));
    }

    public function showNextMonth(Request $request){
        $request_date = $request->date;
        $date = Carbon::parse($request_date);
        $startOfNextMonth = $date->copy()->addMonthNoOverflow()->startOfMonth();
        // dd($startOfNextMonth);
        $endOfNextMonth = $date->copy()->addMonthNoOverflow()->endOfMonth();

        $works = Work::whereBetween('date',[$startOfNextMonth,$endOfNextMonth])->get();

        $month = $startOfNextMonth->format('Y/m');

        $date = $startOfNextMonth->format('Y-m-d');
        
        return view('attendance.list',compact('works','month','date'));
    }

    // 修正申請処理
    public function request(Request $request){
        $user_id = Auth::id();
        $correction = $request -> all();
        dd($correction);
        unset($correction['_token']);
        $correction = array_merge($correction,['user_id' => "$user_id"]);
        dd($correction);
        Correction::create($correction);
       
        // return redirect('/attendance/.$request->work_id');
    }

    public function showWaitingForApproval(){
        $corrections = Correction::where('status','1')->get();
        return view('attendance.correction',compact('corrections'));
    }

    public function showApproved(){
        $corrections = Correction::where('status','2')->get();
        return view('attendance.correction',compact('corrections'));
    }
}
