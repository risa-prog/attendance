<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Work;
use App\Models\Rest;
use App\Models\WorkCorrection;

class CommonController extends Controller
{
    public function showAttendanceDetail($id) {
            $work = Work::find($id);

            $rests = Rest::where('work_id', $id)->get();

            return view('attendance.detail', compact('work', 'rests'));
        }

    public function showCorrectionList(Request $request) {
        if(Auth::guard('web')->check()) {
            $user = Auth::user();

            if ($request->tab === null) {
                $work_corrections = WorkCorrection::where('user_id', $user->id)->get();
                return view('attendance.correction', compact('work_corrections'));
            } elseif ($request->tab === "waiting_for_approval") {
                $work_corrections = WorkCorrection::where([
                    'status' => 1,
                    'user_id' => $user->id
                ])->get();
                return view('attendance.correction', compact('work_corrections'));
            } else {
                $work_corrections = WorkCorrection::where([
                    'status' => 2,
                    'user_id' => $user->id
                ])->get();
                return view('attendance.correction', compact('work_corrections'));
            }

        } elseif (Auth::guard('admin')->check()) {
            if ($request->tab === null) {
                $work_corrections = WorkCorrection::all();
                return view('attendance.correction', compact('work_corrections'));
            } elseif ($request->tab === "waiting_for_approval") {
                $work_corrections = WorkCorrection::where('status',1)->get();
                return view('attendance.correction', compact('work_corrections'));
            } elseif($request->tab === "approved") {
                $work_corrections = WorkCorrection::where('status',2)->get();
                return view('attendance.correction', compact('work_corrections'));
            }
        } 
    }
    
}
