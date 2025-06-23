<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use App\Models\Work;
use App\Models\WorkCorrection;
use App\Models\RestCorrection;
use App\Http\Requests\CorrectionRequest;

class AttendanceController extends Controller
{
    public function showAttendance () {
        $now = Carbon::now();
        $date = $now->format('Y-m-d');

        $user = Auth::user();
        $work = Work::where([
            ['user_id','=',$user->id] ,
            ['date','=',$date]
        ])->first();
        
        return view('attendance.timestamp',compact('now','work'));
    }

    public function showAttendanceList (Request $request) {
        $user = Auth::user();

        if ($request->tab === null) {
            $targetDate = Carbon::now();
            $monthStart = $targetDate->copy()->startOfMonth(); 
            $monthEnd = $targetDate->copy()->endOfMonth();  
            
            $dates = CarbonPeriod::create($monthStart, $monthEnd);

            $works = Work::where('user_id', $user->id)->whereBetween('date', [$monthStart, $monthEnd])
            ->get()
            ->keyBy(fn($work) => Carbon::parse($work->date)->format('Y-m-d')); // 日付でキー指定
        
            return view('attendance.list',compact('targetDate','dates','works'));
        } elseif ($request->tab === "previous") {
            $targetDate = Carbon::parse($request->date);
            
            $startOfPreviousMonth = $targetDate->copy()->subMonth()->startOfMonth();
            $endOfPreviousMonth = $targetDate->copy()->subMonth()->endOfMonth();
            $dates = CarbonPeriod::create($startOfPreviousMonth, $endOfPreviousMonth);
        
            $works = Work::whereBetween('date',[$startOfPreviousMonth,$endOfPreviousMonth])->where('user_id',$user->id)->get()->keyBy(fn($work) => Carbon::parse($work->date)->format('Y-m-d'));

            $targetDate = $startOfPreviousMonth;
    
            return view('attendance.list',compact('targetDate','dates','works'));
        } else {
            $targetDate = Carbon::parse($request->date);
            $startOfNextMonth = $targetDate->copy()->addMonthNoOverflow()->startOfMonth();
            $endOfNextMonth = $targetDate->copy()->addMonthNoOverflow()->endOfMonth();
            $dates = CarbonPeriod::create($startOfNextMonth, $endOfNextMonth);

            $works = Work::whereBetween('date',[$startOfNextMonth,$endOfNextMonth])->where('user_id',$user->id)->get()->keyBy(fn($work) => Carbon::parse($work->date)->format('Y-m-d'));

            $targetDate = $startOfNextMonth;
        
            return view('attendance.list',compact('targetDate','dates','works'));
        }
    }

    // 修正申請処理
    public function request (CorrectionRequest $request) {
        $user_id = Auth::id();
        $work_correction = $request->only(['work_id','work_start','work_end','status','note']);
        $work_correction = array_merge($work_correction,['user_id' => $user_id]);
        WorkCorrection::create($work_correction);
        
        $rest_corrections = $request->only(['rest_start','rest_end','rest_id']);
        
        $rest_id = $rest_corrections['rest_id'];
        $rest_start = $rest_corrections['rest_start'];
        $rest_end = $rest_corrections['rest_end'];

        for($i = 0; $i < count($rest_start); $i++){
            $rest = [
                'rest_id' => optional($rest_id)[$i],
                'rest_start' => $rest_start[$i],
                'rest_end' => $rest_end[$i],
                'work_id' => $work_correction['work_id']
            ];
            
            if ($rest['rest_start'] !== null && $rest['rest_end'] !== null) {
                    RestCorrection::create($rest);
            }
        }
            return redirect()->route('attendance.detail',['id' => $work_correction['work_id']]);
    }
}
