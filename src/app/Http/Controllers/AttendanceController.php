<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\Models\Work;
use App\Models\Rest;
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
        $date = Carbon::now();

        if ($request->tab === null) {
            $works = Work::where('user_id',$user->id)->whereMonth('date',$date->month)->get();

            return view('attendance.list',compact('works','date'));
        } elseif ($request->tab === "previous") {
            $targetDate = Carbon::parse($request->date);
            $startOfPreviousMonth = $targetDate->copy()->subMonth()->startOfMonth();
            $endOfPreviousMonth = $targetDate->copy()->subMonth()->endOfMonth();
        
            $works = Work::whereBetween('date',[$startOfPreviousMonth,$endOfPreviousMonth])->where('user_id',$user->id)->get();

            $date = $startOfPreviousMonth;

            return view('attendance.list',compact('works','date'));
        } else {
            $targetDate = Carbon::parse($request->date);
            $startOfNextMonth = $targetDate->copy()->addMonthNoOverflow()->startOfMonth();
            $endOfNextMonth = $targetDate->copy()->addMonthNoOverflow()->endOfMonth();

            $works = Work::whereBetween('date',[$startOfNextMonth,$endOfNextMonth])->where('user_id',$user->id)->get();

            $date = $startOfNextMonth;
        
            return view('attendance.list',compact('works','date'));
        }
    }

    public function AttendanceDetailRedirect ($id) {
        if (Auth::guard('admin')->check()) {
            return redirect()->route('admin.attendance.detail', ['id' => $id]);
        } elseif (Auth::check('web') && $id === 'list') {
            return redirect()->route('attendance.list');
        } elseif (Auth::check('web')) {
            return redirect()->route('user.attendance.detail', ['id' => $id]);
        }

        return redirect()->route('login');
    }

     public function showAttendanceDetail ($id) {
            $work = Work::find($id);

            $rests = Rest::where('work_id', $id)->get();
            
            return view('attendance.detail',compact('work','rests'));
        }

    public function showCorrectionList (Request $request) {
        $user = Auth::user();
        
        if ($request->tab === null) {
            $work_corrections = WorkCorrection::where('user_id',$user->id)->get();
            return view('attendance.correction',compact('work_corrections'));
        } elseif ($request->tab === "waiting_for_approval") {
            $work_corrections = WorkCorrection::where([
            'status' => '1',
            'user_id' => $user->id
            ])->get();
            return view('attendance.correction',compact('work_corrections'));
        } else {
            $work_corrections = WorkCorrection::where([
            'status' => '2',
            'user_id' => $user->id
            ])->get();
            return view('attendance.correction',compact('work_corrections'));
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
            return redirect()->route('user.attendance.detail',['id' => $work_correction['work_id']]);
    }
}
