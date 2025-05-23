<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use Carbon\Carbon;
use App\Models\Work;
use App\Models\WorkCorrection;
use App\Models\RestCorrection;
use App\Http\Requests\CorrectionRequest;

class AttendanceController extends Controller
{
    public function showAttendance(){
        $now = Carbon::now();
        $date = $now->format('Y-m-d');

        $user = Auth::user();
        $work = Work::where([
            ['user_id','=',$user->id] ,
            ['date','=',$date]
        ])->first();
        
        return view('attendance.timestamp',compact('now','work'));
    }

     public function showAttendanceDetail($id){
        if($id === 'list'){
            $user = Auth::user();
            $date = Carbon::now();

            $works = Work::where('user_id',$user->id)->whereMonth('date',$date->month)->get();

            return view('attendance.list',compact('works','date'));

        }else{
            $work = Work::find($id);
           
            return view('attendance/detail',compact('work'));
        }
     }

    public function showCorrectionList(){
        $user = Auth::user();
        $work_corrections = WorkCorrection::where('user_id',$user->id)->get();
        return view('attendance.correction',compact('work_corrections'));
    }

    public function showPreviousMonth(Request $request){
        $user = Auth::user();
        $request_date = $request->date;
        $targetDate = Carbon::parse($request_date);
        $startOfPreviousMonth = $targetDate->copy()->subMonth()->startOfMonth();
        $endOfPreviousMonth = $targetDate->copy()->subMonth()->endOfMonth();
        
        $works = Work::whereBetween('date',[$startOfPreviousMonth,$endOfPreviousMonth])->where('user_id',$user->id)->get();

        $date = $startOfPreviousMonth;

        return view('attendance.list',compact('works','date'));
    }

    public function showNextMonth(Request $request){
        $user = Auth::user();
        $request_date = $request->date;
        $targetDate = Carbon::parse($request_date);
        $startOfNextMonth = $targetDate->copy()->addMonthNoOverflow()->startOfMonth();
        $endOfNextMonth = $targetDate->copy()->addMonthNoOverflow()->endOfMonth();

        $works = Work::whereBetween('date',[$startOfNextMonth,$endOfNextMonth])->where('user_id',$user->id)->get();

        $date = $startOfNextMonth;
        
        return view('attendance.list',compact('works','date'));
    }

    // 修正申請処理
    public function request(CorrectionRequest $request){
        $user_id = Auth::id();
        $work_correction = $request->only(['work_id','work_start','work_end','status','note']);
        $work_correction = array_merge($work_correction,['user_id' => $user_id]);
        WorkCorrection::create($work_correction);
        
        $rest_corrections = $request->only(['rest_start','rest_end','rest_id']);
        
        $rest_id = $rest_corrections['rest_id'];
        $rest_start = $rest_corrections['rest_start'];
        $rest_end = $rest_corrections['rest_end'];
        
        for($i = 0; $i < count($rest_id); $i++){
            $rest = [
                'rest_id' => $rest_id[$i],
                'rest_start' => $rest_start[$i],
                'rest_end' => $rest_end[$i],
                'work_id' => $work_correction['work_id']
            ];

           if($rest['rest_start'] !== null && $rest['rest_end'] !== null){
            RestCorrection::create($rest);
           }
        }

        if($request->rest_start2 != null && $request->rest_end2 != null){
            $rest = [
                'rest_start' => $request->rest_start2,
                'rest_end' => $request->rest_end2,
                'work_id' => $work_correction['work_id'],
            ];
            RestCorrection::create($rest);
        }

        return redirect()->route('work_detail',['id' => $work_correction['work_id']]);
       
    }

    public function showWaitingForApproval(){
        $user = Auth::user();
        $work_corrections = WorkCorrection::where([
            'status' => '1',
            'user_id' => $user->id
        ])->get();
        return view('attendance.correction',compact('work_corrections'));
    }

    public function showApproved(){
        $user = Auth::user();
        $work_corrections = WorkCorrection::where([
            'status' => '2',
            'user_id' => $user->id
        ])->get();
        return view('attendance.correction',compact('work_corrections'));
    }
}
